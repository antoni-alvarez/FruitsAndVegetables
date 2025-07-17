# Fruit & Vegetables Code Challenge 🍎🥦

## ⚙️ Prerequisites

- Docker
- Make

## 🚀 How to start

To spin up the project using Docker:

```bash
make start
```

Once started, the project will be accessible at:

```
http://localhost:8080
```

## 📥 Importing data

You can import food items from the JSON file using this command:

```bash
bin/console app:import assets/data/request.json
```

## 🔗 API Endpoints

#### `GET /food`

Returns all food items. Optional query params filters:

- `type`: `"fruit"`, `"vegetable"`, or omitted to list all
- `unit`: `"g"` (default) or `"kg"`

---

#### `POST /food`

Adds a new food item.

**Request body example in JSON format:**

```json
{
  "id": 21,
  "name": "Mango",
  "type": "fruit",
  "quantity": 2,
  "unit": "kg"
}
```

### API Requests

You can find example requests for the endpoints in the following file:

```
app/requests/food_requests.http
```

## 🧪 Running tests

```bash
make test
```

## 👤 Author

Created by [Antoni Alvarez](https://github.com/antoni-alvarez)
