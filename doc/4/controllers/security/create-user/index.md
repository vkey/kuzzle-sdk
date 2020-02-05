---
code: true
type: page
title: createUser
description: Security:createUser
---

# createUser

Creates a new user in Kuzzle.

:::info
There is a small delay between user creation and its availability in our search layer (usually a couple of seconds).
That means that a user that was just created may not be returned by the `searchUsers` function at first.
:::

---

## createUser(id, user, [options], [callback])

| Arguments  | Type                   | Description                                                                                                                  |
| ---------- | -----------            | ---------------------------------------------------------------------------------------------------------------------------- |
| `id`       | <pre>string</pre>      | [Unique user identifier](/core/1/guides/essentials/user-authentication#kuzzle-user-identifier-kuid)                   |
| `user`     | <pre>JSON Object</pre> | A plain JSON object representing the user (see below)                                                                        |
| `options`  | <pre>string</pre>      | (Optional) Optional arguments                                                                                                |
| `callback` | <pre>function</pre>    | Callback handling the response                                                                                               |
| `refresh`  | <pre>string</pre>      | If set to `wait_for`, Kuzzle will wait the persistence layer to finish indexing |

The `user` object to provide must have the following properties:

- `content` (JSON object): user global properties
  - This object must contain a `profileIds` properties, an array of strings listing the security [profiles](/core/1/guides/essentials/security#users-profiles-and-roles) to be attached to the new user
  - Any other property will be copied as additional global user information
- `credentials` (JSON object): a description of how the new user can identify themselves on Kuzzle
  - Any number of credentials can be added, each one being an object with name equal to the [authentication strategy](/core/1/plugins/guides/strategies#exposing-authentication-strategies) used to authenticate the user, and with the login data as content.
  - If this object is left empty, the user will be created in Kuzzle but the will not be able to login.

---

## Options

| Filter     | Type               | Description                      
| ---------- | -------            | ---------------------------------
| `queuable` | <pre>boolean</pre> | If true, queues the request during downtime, until connected to Kuzzle again

---

## Callback Response

Returns a `User` object.

## Usage

<<< ./snippets/create-user-1.php