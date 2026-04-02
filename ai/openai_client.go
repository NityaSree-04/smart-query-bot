package ai

import (
	"context"
	"fmt"

	"db-chat-ai/database"

	openai "github.com/sashabaranov/go-openai"
)

type OpenAIClient struct {
	client *openai.Client
	schema []database.TableInfo
	model  string
}

func NewOpenAIClient(apiKey string, baseURL string, model string, schema []database.TableInfo) *OpenAIClient {
	config := openai.DefaultConfig(apiKey)
	if baseURL != "" && baseURL != "https://api.openai.com/v1" {
		config.BaseURL = baseURL
	}
	
	return &OpenAIClient{
		client: openai.NewClientWithConfig(config),
		schema: schema,
		model:  model,
	}
}

func (c *OpenAIClient) GenerateSQL(userQuestion string) (string, error) {
	prompt := BuildSQLPrompt(c.schema, userQuestion)

	resp, err := c.client.CreateChatCompletion(
		context.Background(),
		openai.ChatCompletionRequest{
			Model: c.model,
			Messages: []openai.ChatCompletionMessage{
				{
					Role:    openai.ChatMessageRoleSystem,
					Content: prompt.System,
				},
				{
					Role:    openai.ChatMessageRoleUser,
					Content: prompt.User,
				},
			},
			Temperature: 0.1,
			MaxTokens:   500,
		},
	)

	if err != nil {
		return "", fmt.Errorf("OpenAI API error: %w", err)
	}

	if len(resp.Choices) == 0 {
		return "", fmt.Errorf("no response from OpenAI")
	}

	sqlQuery := resp.Choices[0].Message.Content
	return sqlQuery, nil
}

func (c *OpenAIClient) FormatResponse(userQuestion string, queryResult *database.QueryResult) (string, error) {
	resultJSON, err := queryResult.ToJSON()
	if err != nil {
		return "", err
	}

	systemPrompt := `You are a helpful assistant that formats database query results into natural, conversational responses.
Your task is to take the user's question and the query results, and provide a clear, human-readable answer.
Be concise but informative. If there are multiple rows, summarize the key findings.`

	userPrompt := fmt.Sprintf(`User asked: "%s"

Query results:
%s

Please provide a natural language response to the user's question based on these results.`, userQuestion, resultJSON)

	resp, err := c.client.CreateChatCompletion(
		context.Background(),
		openai.ChatCompletionRequest{
			Model: c.model,
			Messages: []openai.ChatCompletionMessage{
				{
					Role:    openai.ChatMessageRoleSystem,
					Content: systemPrompt,
				},
				{
					Role:    openai.ChatMessageRoleUser,
					Content: userPrompt,
				},
			},
			Temperature: 0.7,
			MaxTokens:   500,
		},
	)

	if err != nil {
		return "", fmt.Errorf("OpenAI API error: %w", err)
	}

	if len(resp.Choices) == 0 {
		return "", fmt.Errorf("no response from OpenAI")
	}

	return resp.Choices[0].Message.Content, nil
}
