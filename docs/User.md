# User


| Name        | Type           | Description  |
|:-----------:|:--------------:|------------:|
| PATH     | /users | Patch an existing model instance or insert a new one into the data source. |
| GET     | /users | Find all instances of the model matched by filter from the data source. |
| PUT     | /users | Replace an existing model instance or insert a new one into the data source. |
| POST    | /users   | Create a new instance of the model and persist it into the data source. |
| GET    | /Users/{id}   | Find a model instance by {{id}} from the data source. |
| HEAD    | /Users/{id}   | Check whether a model instance exists in the data source. |
| PUT    | /Users/{id}   | Replace attributes for a model instance and persist it into the data source. |
| DELETE    | /Users/{id}   | Delete a model instance by {{id}} from the data source. |
| GET    | /Users/{id}/accessTokens   | Queries accessTokens of User. |
| POST    | /Users/{id}/accessTokens   | Creates a new instance in accessTokens of this model. |
| DELETE    | /Users/{id}/accessTokens   | Deletes all accessTokens of this model. |
| POST    | /Users/change-password   | Deletes all accessTokens of this model. |
| POST    | /Users/login   | Login a user with username/email and password. |
| POST    | /Users/logout  | Logout a user with access token. |
| POST    | /Users/reset-password | Reset user's password via a password-reset token. |
ergweprjgwerighjwethjtr2315