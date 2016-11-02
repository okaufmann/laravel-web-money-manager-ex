# Web Money Manager EX API Definitions

## Common
**Request base URI**: http://localhost:2015

**Client**
Money Manager EX uses the following Code to communicate with the web API:
https://github.com/moneymanagerex/moneymanagerex/blob/2b9497172fd299731065b53407cbb6b8e44bf440/src/webapp.cpp

## Web Client Endpoints:

### Get default category for payee

**URI**: /query.php?get_default_category=Migros

**Example Result**
```json
{"PayeeName":"Migros","DefCateg":"Test","DefSubCateg":"test"}
```

### Get default category for parent category

**URI**: /query.php?get_subcategory=Test

**Example Result**
```json
[{"SubCategoryName":"test"}]
```

---

## Offline Client Endpoints

### Common Authentication through GUID

Request from the client contains an GUID to authorize (e.g. {D6A33C24-DE43-D62C-A609-EF5138F33F27})
```
http://localhost:2015?guid=%7BD6A33C24-DE43-D62C-A609-EF5138F33F27%7D
```

**Example Result for success**
```text
Operation has succeeded
```

**Example Result invalid GUID provided**
```text
Wrong GUID
```

### Payees
#### Delete
**Url:** `GET`  `&delete_payee`

#### Import

**Url:** `POST`  `&import_payee=true`

**Example Payload**
A Json Object the Property `MMEX_Post` contains the Data as Text value (escaped json).
```json
{"MMEX_Post":"{ \"Payees\" : [ { \"PayeeName\" : \"Mc Donalds\", \"DefCateg\" : \"None\", \"DefSubCateg\" : \"None\" }, { \"PayeeName\" : \"Spotify\", \"DefCateg\" : \"None\", \"DefSubCateg\" : \"None\" } ] } "}
```

### Accounts

#### Delete
**Url:** `GET`  `&delete_bankaccount`

#### Import

**Url:** `POST`  `&import_bankaccount=true`

**Example Payload**
A Json Object the property `MMEX_Post` contains the Data as Text value (escaped json).
```json 
{"MMEX_Post":"{ \"Accounts\" : [ { \"AccountName\" : \"Creditcard\" }, { \"AccountName\" : \"Private Account\" } ] }"}
```

### Categories

####Delete
**Url:** `GET`  `&delete_category`

#### Import

**Url:** `POST`  `&import_category=true`

**Example Payload**
A Json Object the property `MMEX_Post` contains the Data as Text value (escaped json).
```json 
{"MMEX_Post":"{ \"Categories\" : [ { \"CategoryName\" : \"Bills\", \"SubCategoryName\" : \"Telecom\" }, { \"CategoryName\" : \"Bills\", \"SubCategoryName\" : \"Water\" }, { \"CategoryName\" : \"Automobile\", \"SubCategoryName\" : \"Maintenance\" }, { \"CategoryName\" : \"Automobile\", \"SubCategoryName\" : \"Parking\" } ] }"}
```

### Transactions

**TODO**