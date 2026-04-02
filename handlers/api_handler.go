package handlers

import (
	"encoding/json"
	"net/http"

	"db-chat-ai/ai"
	"db-chat-ai/database"
)

type APIHandler struct {
	db       *database.DBConnection
	aiClient *ai.OpenAIClient
}

func NewAPIHandler(db *database.DBConnection, aiClient *ai.OpenAIClient) *APIHandler {
	return &APIHandler{
		db:       db,
		aiClient: aiClient,
	}
}

type QueryRequest struct {
	Question string `json:"question"`
}

type QueryResponse struct {
	Answer string                `json:"answer"`
	SQL    string                `json:"sql"`
	Result *database.QueryResult `json:"result,omitempty"`
	Error  string                `json:"error,omitempty"`
}

func (h *APIHandler) HandleQuery(w http.ResponseWriter, r *http.Request) {
	if r.Method != http.MethodPost {
		http.Error(w, "Method not allowed", http.StatusMethodNotAllowed)
		return
	}

	var req QueryRequest
	if err := json.NewDecoder(r.Body).Decode(&req); err != nil {
		respondWithError(w, "Invalid request body", http.StatusBadRequest)
		return
	}

	// Generate SQL
	sqlQuery, err := h.aiClient.GenerateSQL(req.Question)
	if err != nil {
		respondWithError(w, "Failed to generate SQL: "+err.Error(), http.StatusInternalServerError)
		return
	}

	// Execute query
	result, err := database.ExecuteQuery(h.db, sqlQuery)
	if err != nil {
		response := QueryResponse{
			SQL:   sqlQuery,
			Error: "Query execution failed: " + err.Error(),
		}
		respondWithJSON(w, response, http.StatusOK)
		return
	}

	// Format response
	answer, err := h.aiClient.FormatResponse(req.Question, result)
	if err != nil {
		respondWithError(w, "Failed to format response: "+err.Error(), http.StatusInternalServerError)
		return
	}

	response := QueryResponse{
		Answer: answer,
		SQL:    sqlQuery,
		Result: result,
	}
	respondWithJSON(w, response, http.StatusOK)
}

func (h *APIHandler) HandleGetTables(w http.ResponseWriter, r *http.Request) {
	if r.Method != http.MethodGet {
		http.Error(w, "Method not allowed", http.StatusMethodNotAllowed)
		return
	}

	schema, err := h.db.GetSchema()
	if err != nil {
		respondWithError(w, "Failed to get schema: "+err.Error(), http.StatusInternalServerError)
		return
	}

	respondWithJSON(w, schema, http.StatusOK)
}

func (h *APIHandler) HandleHealth(w http.ResponseWriter, r *http.Request) {
	respondWithJSON(w, map[string]string{"status": "healthy"}, http.StatusOK)
}

func respondWithJSON(w http.ResponseWriter, data interface{}, statusCode int) {
	w.Header().Set("Content-Type", "application/json")
	w.WriteHeader(statusCode)
	json.NewEncoder(w).Encode(data)
}

func respondWithError(w http.ResponseWriter, message string, statusCode int) {
	respondWithJSON(w, map[string]string{"error": message}, statusCode)
}
