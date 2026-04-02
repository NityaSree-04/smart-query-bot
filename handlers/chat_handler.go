package handlers

import (
	"encoding/json"
	"fmt"
	"log"
	"net/http"
	"sync"

	"db-chat-ai/ai"
	"db-chat-ai/database"

	"github.com/gorilla/websocket"
)

var upgrader = websocket.Upgrader{
	CheckOrigin: func(r *http.Request) bool {
		return true // Allow all origins for development
	},
}

type ChatHandler struct {
	db       *database.DBConnection
	aiClient *ai.OpenAIClient
	clients  map[*websocket.Conn]bool
	mu       sync.Mutex
}

type ChatMessage struct {
	Type    string `json:"type"`    // "user" or "assistant" or "error"
	Message string `json:"message"`
	SQL     string `json:"sql,omitempty"`
}

func NewChatHandler(db *database.DBConnection, aiClient *ai.OpenAIClient) *ChatHandler {
	return &ChatHandler{
		db:       db,
		aiClient: aiClient,
		clients:  make(map[*websocket.Conn]bool),
	}
}

func (h *ChatHandler) HandleWebSocket(w http.ResponseWriter, r *http.Request) {
	conn, err := upgrader.Upgrade(w, r, nil)
	if err != nil {
		log.Printf("WebSocket upgrade error: %v", err)
		return
	}
	defer conn.Close()

	// Register client
	h.mu.Lock()
	h.clients[conn] = true
	h.mu.Unlock()

	// Send welcome message
	welcomeMsg := ChatMessage{
		Type:    "assistant",
		Message: "👋 Hello! I'm your AI database assistant. Ask me anything about your data!",
	}
	conn.WriteJSON(welcomeMsg)

	// Unregister client on disconnect
	defer func() {
		h.mu.Lock()
		delete(h.clients, conn)
		h.mu.Unlock()
	}()

	// Listen for messages
	for {
		var msg ChatMessage
		err := conn.ReadJSON(&msg)
		if err != nil {
			if websocket.IsUnexpectedCloseError(err, websocket.CloseGoingAway, websocket.CloseAbnormalClosure) {
				log.Printf("WebSocket error: %v", err)
			}
			break
		}

		// Process user question
		if msg.Type == "user" {
			h.processQuestion(conn, msg.Message)
		}
	}
}

func (h *ChatHandler) processQuestion(conn *websocket.Conn, question string) {
	// Send thinking message
	thinkingMsg := ChatMessage{
		Type:    "assistant",
		Message: "🤔 Let me think about that...",
	}
	conn.WriteJSON(thinkingMsg)

	// Generate SQL from question
	sqlQuery, err := h.aiClient.GenerateSQL(question)
	if err != nil {
		errorMsg := ChatMessage{
			Type:    "error",
			Message: fmt.Sprintf("Failed to generate SQL: %v", err),
		}
		conn.WriteJSON(errorMsg)
		return
	}

	// Execute the SQL query
	result, err := database.ExecuteQuery(h.db, sqlQuery)
	if err != nil {
		errorMsg := ChatMessage{
			Type:    "error",
			Message: fmt.Sprintf("Query execution failed: %v", err),
			SQL:     sqlQuery,
		}
		conn.WriteJSON(errorMsg)
		return
	}

	// Format the response using AI
	response, err := h.aiClient.FormatResponse(question, result)
	if err != nil {
		errorMsg := ChatMessage{
			Type:    "error",
			Message: fmt.Sprintf("Failed to format response: %v", err),
		}
		conn.WriteJSON(errorMsg)
		return
	}

	// Send the formatted response
	responseMsg := ChatMessage{
		Type:    "assistant",
		Message: response,
		SQL:     sqlQuery,
	}
	conn.WriteJSON(responseMsg)
}
