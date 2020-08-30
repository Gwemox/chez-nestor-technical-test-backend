# Test API Backend - Chez Nestor

## A-propos

Application écrite en PHP sous le framework Symphony 5.1.* avec une base de données en PostgreSQL.
L'application est dockerisé pour rendre le test plus facile !

Énoncé : https://github.com/chez-nestor/technical-test-backend

Toutes les règles sont respectées :
- Un appartement contient au moins 1 chambre.
- Un client ne peut pas réserver plusieurs chambres en même temps.
- L'adresse email d'un client est unique.
- Lorsqu'une chambre a été réservée par un client, elle ne peut plus être réservée.
- Une chambre peut être réservée seulement si le client a rempli toutes ses informations.

## Comment la lancer ?

Sur une machine disposant de Docker, Docker-Compose et une connexion interne :

Lancement des tests unitaires / intégrations :

`docker-compose -f docker-compose.test.yaml up --abort-on-container-exit --build`

---
Lancement en local sur sa machine (BDD + PHP + API) : 

`docker-compose -f docker-compose.yaml up --build`

L'application est ensuite disponible sur le port 80 de votre machine : http://localhost/

---
Cette méthode n'est pas recommandée.
Lancement dans le but de développer de BDD + PHP + API : 

`docker-compose -f docker-compose.dev.yaml up --build`

L'application est ensuite disponible sur le port 80 de votre machine : http://localhost/

---

## API Endpoints ?

#### Appartements
##### Créer un appartement
URL : `POST /apartments` : 
##### Exemple
Body :
`{
      "name": "Chez Toto",
      "street": "12 rue du nestor",
      "zipCode": "69006",
      "city": "Lyon"
 }`
 
Resultat :
`{
      "id": "c66bd9b0-d3ac-4f42-8473-691cbef397c1",
      "name": "Chez Toto",
      "street": "12 rue du nestor",
      "zipCode": "69006",
      "city": "Lyon"
 }`

Status code :
- 201 : création réussite
- 4xx : erreur de requête (client)
- 5xx : erreur serveur
##### Lister tous les appartements
URL : `GET /apartments` : 
##### Exemple
Resultat :
`[
{
     "id": "c66bd9b0-d3ac-4f42-8473-691cbef397c1",
     "name": "Chez Toto",
     "street": "12 rue du nestor",
     "zipCode": "69006",
     "city": "Lyon"
}, {...}, ...]`

Status code :
- 200 : résultat
- 4xx : erreur de requête (client)
- 5xx : erreur serveur

##### Récupérer un appartement
URL : `GET /apartments/{apartmentId}` : 
##### Exemple
Resultat :
`{
     "id": "c66bd9b0-d3ac-4f42-8473-691cbef397c1",
     "name": "Chez Toto",
     "street": "12 rue du nestor",
     "zipCode": "69006",
     "city": "Lyon"
}`

Status code :
- 200 : résultat
- 4xx : erreur de requête (client)
- 5xx : erreur serveur

---

##### Modifier un appartement
URL : `PATCH /apartments/{apartmentId}` : 
##### Exemple
Body :
`{
     "id": "39844656-e57f-4c1c-8284-93ee643b34b7",
     "name": "Chez toto edition 2"
 }`
 
Resultat :
``

Status code :
- 204 : action réalisée, aucun contenu
- 4xx : erreur de requête (client)
- 5xx : erreur serveur

---
##### Supprimer un appartement
URL : `DELETE /apartments/{apartmentId}` : 
##### Exemple
Resultat :
`{
    "id": "26413985-d5684f-7c2d-1153-36bb546f21e3",
     "name": "Chez toto",
     "street": "12 rue sablier",
     "zipCode": "69002",
     "city": "Lyon",
    "rooms": [...]
}`

Status code :
- 200 : suppression réussite
- 4xx : erreur de requête (client)
- 5xx : erreur serveur

---

#### Chambres
##### Créer une chambre
URL : `POST /rooms` : 
##### Exemple
Body :
`{
     "number": 41,
     "area": 45.59,
     "price": 451,
     "apartment": {
         "name": "Chez Joe",
         "street": "xxx",
         "zip_code": "69004",
         "city": "Lyon"
     }
 }`
 
Resultat :
`{
     "id": "4e814948-2a83-4c1c-9a04-22c08ac73385",
     "number": 41,
     "area": 45.59,
     "price": 451,
     "apartment": {
         "id": "a2936c0e-e089-4f54-94be-e8016830cf1c",
         "name": "Chez Joe",
         "street": "xxx",
         "zip_code": "69004",
         "city": "Lyon",
         "rooms": []
     }
 }`

Status code :
- 201 : création réussite
- 4xx : erreur de requête (client)
- 5xx : erreur serveur
##### Lister tous les chambres
URL : `GET /rooms` : 
##### Exemple
Resultat :
`[
     {
         "id": "4e814948-2a83-4c1c-9a04-22c08ac73385",
         "number": 41,
         "area": 45.59,
         "price": 451,
         "apartment": {
             "id": "a2936c0e-e089-4f54-94be-e8016830cf1c",
             "name": "Chez thib",
             "street": "xxx",
             "zip_code": "69004",
             "city": "Lyon",
             "rooms": []
         }
     }
 ,{...}, ...]`

Status code :
- 200 : résultat
- 4xx : erreur de requête (client)
- 5xx : erreur serveur

##### Lister tous les chambres d'un appartement
URL : `GET /rooms/apartment/{apartmentId}` : 
##### Exemple
Resultat :
`[
     {
         "id": "4e814948-2a83-4c1c-9a04-22c08ac73385",
         "number": 41,
         "area": 45.59,
         "price": 451,
         "apartment": {
             "id": "a2936c0e-e089-4f54-94be-e8016830cf1c",
             "name": "Chez thib",
             "street": "xxx",
             "zip_code": "69004",
             "city": "Lyon",
             "rooms": []
         }
     }
 ,{...}, ...]`

Status code :
- 200 : résultats
- 4xx : erreur de requête (client)
- 5xx : erreur serveur

##### Récupérer une chambre
URL : `GET /rooms/{roomId}` : 
##### Exemple
Resultat :
`{
    "id": "4e814948-2a83-4c1c-9a04-22c08ac73385",
    "number": 41,
    "area": 45.59,
    "price": 451,
    "apartment": {
        "id": "a2936c0e-e089-4f54-94be-e8016830cf1c",
        "name": "Chez thib",
        "street": "xxx",
        "zip_code": "69004",
        "city": "Lyon",
        "rooms": []
    }
}`

Status code :
- 200 : résultat
- 4xx : erreur de requête (client)
- 5xx : erreur serveur

---

##### Modifier une chambre
URL : `PATCH /rooms/{roomId}` : 
##### Exemple
Body :
`{
     "id": "4e814948-2a83-4c1c-9a04-22c08ac73385",
     "number": 638
 }`
 
Resultat :
``

Status code :
- 204 : action réalisée, aucun contenu
- 4xx : erreur de requête (client)
- 5xx : erreur serveur

---
##### Supprimer une chambre
URL : `DELETE /rooms/{roomId}` : 
##### Exemple
Resultat :
`{
    "id": "4e814948-2a83-4c1c-9a04-22c08ac73385",
    "number": 41,
    "area": 45.59,
    "price": 451,
    "apartment": {
        "id": "a2936c0e-e089-4f54-94be-e8016830cf1c",
        "name": "Chez thib",
        "street": "xxx",
        "zip_code": "69004",
        "city": "Lyon",
        "rooms": []
    }
}`

Status code :
- 200 : suppression réussite
- 4xx : erreur de requête (client)
- 5xx : erreur serveur

---

#### Clients
##### Créer un client
URL : `POST /clients` : 
##### Exemple
Body :
`{
     "first_name": "John",
     "last_name": "Doe",
     "email": "john.doe@anonymous.com",
     "phone": "0123456789",
     "birth_date": "1992-04-30T00:00:00+00:00",
     "nationality": "Français"
}`
 
Resultat :
`{
      "id": "2f3b0d82-e16a-46c6-8475-682daa71c7e2",
      "first_name": "John",
      "last_name": "Doe",
      "email": "john.doe@anonymous.com",
      "phone": "0123456789",
      "birth_date": "1992-04-30T00:00:00+00:00",
      "nationality": "Français"
  }`

Status code :
- 201 : création réussite
- 4xx : erreur de requête (client)
- 5xx : erreur serveur
##### Lister tous les clients
URL : `GET /clients` : 
##### Exemple
Resultat :
`[
     {
        "id": "2f3b0d82-e16a-46c6-8475-682daa71c7e2",
        "first_name": "John",
        "last_name": "Doe",
        "email": "john.doe@anonymous.com",
        "phone": "0123456789",
        "birth_date": "1992-04-30T00:00:00+00:00",
        "nationality": "Français"
     }
 ,{...}, ...]`

Status code :
- 200 : résultat
- 4xx : erreur de requête (client)
- 5xx : erreur serveur

##### Récupérer un client
URL : `GET /clients/{clientId}` : 
##### Exemple
Resultat :
`{
    "id": "2f3b0d82-e16a-46c6-8475-682daa71c7e2",
    "first_name": "John",
    "last_name": "Doe",
    "email": "john.doe@anonymous.com",
    "phone": "0123456789",
    "birth_date": "1992-04-30T00:00:00+00:00",
    "nationality": "Français"
}`

Status code :
- 200 : résultat
- 4xx : erreur de requête (client)
- 5xx : erreur serveur

---

##### Modifier un client
URL : `PATCH /clients/{clientId}` : 
##### Exemple
Body :
`{
    "id": "2f3b0d82-e16a-46c6-8475-682daa71c7e2",
    "nationality": "Anglais"
 }`
 
Resultat :
``

Status code :
- 204 : action réalisée, aucun contenu
- 4xx : erreur de requête (client)
- 5xx : erreur serveur

---
##### Supprimer un client
URL : `DELETE /clients/{clientId}` : 
##### Exemple
Resultat :
`{
    "id": "2f3b0d82-e16a-46c6-8475-682daa71c7e2",
    "first_name": "John",
    "last_name": "Doe",
    "email": "john.doe@anonymous.com",
    "phone": "0123456789",
    "birth_date": "1992-04-30T00:00:00+00:00",
    "nationality": "Français"
}`

Status code :
- 200 : suppression réussite
- 4xx : erreur de requête (client)
- 5xx : erreur serveur

---

#### Réservations
##### Créer une réservation
URL : `POST /bookings` : 
##### Exemple
Body :
`{
     "begin_at": "2020-09-14T00:00:00+00:00",
     "finish_at": "2020-09-14T00:00:00+00:00",
     "client": "e16c69eb-c6ff-440d-9a02-ce67bb5b38be",
     "room": "e16c69eb-c6ff-440d-9a02-ce67bb5b38be"
 }`
 
Resultat :
`{
      "id": "e16c69eb-c6ff-440d-9a02-ce67bb5b38be",
      "begin_at": "2020-09-14T00:00:00+00:00",
      "finish_at": "2020-09-14T00:00:00+00:00",
      "client": {
          ...
      },
      "room": {
        ...
      }
  }`

Status code :
- 201 : création réussite
- 4xx : erreur de requête (client)
- 5xx : erreur serveur

##### Lister toutes les réservations
URL : `GET /bookings` : 
##### Exemple
Resultat :
`[
{
    "id": "e16c69eb-c6ff-440d-9a02-ce67bb5b38be",
    "begin_at": "2020-09-14T00:00:00+00:00",
    "finish_at": "2020-09-14T00:00:00+00:00",
    "client": {
        ...
    },
    "room": {
      ...
    }
}
 ,{...}, ...]`

Status code :
- 200 : résultat
- 4xx : erreur de requête (client)
- 5xx : erreur serveur

##### Récupérer une réservation
URL : `GET /bookings/{bookingId}` : 
##### Exemple
Resultat :
`{
     "id": "e16c69eb-c6ff-440d-9a02-ce67bb5b38be",
     "begin_at": "2020-09-14T00:00:00+00:00",
     "finish_at": "2020-09-14T00:00:00+00:00",
     "client": {
         ...
     },
     "room": {
       ...
     }
 }`

Status code :
- 200 : résultat
- 4xx : erreur de requête (client)
- 5xx : erreur serveur

---

##### Modifier une réservation
URL : `PATCH /bookings/{bookingId}` : 
##### Exemple
Body :
`{
     "id": "e16c69eb-c6ff-440d-9a02-ce67bb5b38be",
     "begin_at": "2020-09-14T00:00:00+00:00",
     "finish_at": "2020-09-15T00:00:00+00:00",
 }`
 
Resultat :
``

Status code :
- 204 : action réalisée, aucun contenu
- 4xx : erreur de requête (client)
- 5xx : erreur serveur

---
##### Supprimer une réservation
URL : `DELETE /bookings/{bookingId}` : 
##### Exemple
Resultat :
`{
     "id": "e16c69eb-c6ff-440d-9a02-ce67bb5b38be",
     "begin_at": "2020-09-14T00:00:00+00:00",
     "finish_at": "2020-09-14T00:00:00+00:00",
     "client": {
         ...
     },
     "room": {
       ...
     }
 }`

Status code :
- 200 : suppression réussite
- 4xx : erreur de requête (client)
- 5xx : erreur serveur
---