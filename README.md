# php_chat

### Introduction
- GraphQL is a query language for APIs and a runtime for fulfilling those queries with your existing data. 
GraphQL provides a complete and understandable description of the data in your API, gives clients the power 
to ask for exactly what they need and nothing more, makes it easier to evolve APIs over time, and enables 
powerful developer tools.

### Installation

##### Running the server.
steps : Install all the packages
 - ```composer install```
 - ```php -S localhost:8000 api.php```   

### Packages used:
-  [graphql-php](https://github.com/webonyx/graphql-php)
-  [Siler](https://siler.leocavalcante.dev/graphql/)
-  [MongoDb](https://www.mongodb.com/)   

### Queries
- Get all rooms  
 ```curl -d '{"query": "query{ rooms{ id name messages{ id body timestamp roomId  } } }" }' -H "Content-Type: application/json" http://localhost:8000/api.php```
- Start new room  
 ```curl -d '{"query": "mutation { start(roomName:\"RoomName\") { id name } }" }' -H "Content-Type: application/json" http://localhost:8000/api.php```
- Get messages of 1 room  
 ```curl -d '{"query": "query { messages(roomName:\"RoomName\") { id roomId body timestamp } }" }' -H "Content-Type: application/json" http://localhost:8000/api.php```
- Add new message  
 ```curl -d '{"query": "mutation { chat(roomName:\"RoomName\", body:\"MessageBody\") { id } }" }' -H "Content-Type: application/json" http://localhost:8000/api.php```

***

- Server is build using Php
- MongoDb as the database