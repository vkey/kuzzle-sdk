---
code: true
type: page
title: setProfiles
description: User:setProfiles
---

# setProfiles

Replaces the security profiles linked to the user.

:::info
Updating a user will have no impact until either the [create](/sdk/php/3/core-classes/user/create) or [replace](/sdk/php/3/core-classes/user/replace) method is called.
:::

---

## setProfiles(profileIds)

| Arguments    | Type             | Description         |
| ------------ | ---------------- | ------------------- |
| `profileIds` | array of strings | List of profile IDs |

---

## setProfiles(profiles)

| Arguments  | Type                     | Description                                                    |
| ---------- | ------------------------ | -------------------------------------------------------------- |
| `profiles` | array of Profile objects | An array of instantiated [Profile](/sdk/php/3/core-classes/profile) objects |

---

## Return Value

Returns the `User` object.

## Usage

<<< ./snippets/set-profiles-1.php
