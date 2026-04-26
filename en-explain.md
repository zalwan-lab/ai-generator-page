## 1. Tech Stack

* **Backend Framework:** Laravel 11.x (using PHP 8.2)
* **Frontend:** Blade Templates combined with Vite and Tailwind CSS 3.4 for styling
* **Database:** MySQL
* **AI Integration:** OpenAI API (also supports other compatible APIs such as Groq, OpenRouter, vLLM, or Ollama). Communication is handled through Laravel HTTP Client and uses a strict `json_schema` response structure.

## 2. How the System Works

This system is designed as a platform (similar to SaaS) that allows users to instantly generate sales pages with the help of AI.

* **Account & Workspace Management:** Users must register and log in. All data is managed within a **Workspace** system, allowing users to separate sales pages based on different projects or businesses.
* **AI Role:** Through a *System Prompt*, the AI is instructed to act as a *Senior Conversion Copywriter* and *Brand Designer*. Its responsibility is not only to create persuasive copywriting but also to determine the most suitable *visual theme* (color palette and mood) for the product.
* **Context System (Context Manager):** The system stores the history of users’ sales page generation within the related workspace so that future AI generations maintain consistent writing style and tone.
* **Fallback Mechanism:** If the AI integration encounters issues (such as timeouts or invalid API keys), the system provides a fallback function to still generate a sales page using a standard structure based on the user’s raw input.

## 3. Flow from Input → Output

1. **User Input Data:** Users access a form and input basic product information such as `product_name`, `description`, `features`, `target_audience`, `price`, `usp` (unique selling point), and `tone` (writing style).

2. **Smart Suggestions (Optional):** If users are unsure how to fill out the form, they can request AI suggestions using `suggest()`. The AI reads the temporary input and provides 3–6 short writing options for specific fields in real time.

3. **Processing Stage (System):** Once submitted, the data is combined with context from `ContextManager`, then sent to the OpenAI API by `SalesPageGenerator` with strict instructions to generate copy components using the AIDA marketing framework (*Attention, Interest, Desire, Action*).

4. **Content Generation (AI Output):** The AI responds with a clean, structured JSON data format containing:

   * **Copywriting:** Headline, Subheadline, Description, Benefits, Features, Social Proof, Call to Action, Price
   * **Theme:** Color palette (e.g., violet, emerald) and mood (e.g., minimal, bold, playful)

5. **Visual Output (Final Result):** The system stores the JSON data in the MySQL database and redirects the user to the preview page (`/sales-pages/{id}/preview`). There, Blade Templates and Tailwind CSS render the JSON response into a complete, visually appealing, and ready-to-use Landing/Sales Page.
