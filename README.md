# Zadanie

## Opis

W repozytorium znajduje się projekt złożony z dwóch aplikacji:
- Phoenix API
- Symfony App

## Setup aplikacji - build

Aby uruchomić aplikacje, należy zainstalować Docker i Docker Compose.
Następnie należy uruchomić komendę:

```bash
make up
```
lub

```bash
docker compose up -d
```

## Setup aplikacji - dev mode

Aby uruchomić aplikacje w trybie dev (z montowaniem php), należy zainstalować Docker i Docker Compose.
Następnie należy uruchomić komendę:
```bash
make up-dev
```
lub
```bash
docker compose -f docker-compose.dev.yaml up -d
```

## Czyszczenie aplikacji - build

Aby wyczyścić aplikacje, należy uruchomić komendę:
```bash
make down
```
lub
```bash
docker compose down
```

## Czyszczenie aplikacji - dev mode

Aby wyczyścić aplikacje, należy uruchomić komendę:
```bash
make down-dev
```
lub
```bash
docker compose -f docker-compose.dev.yaml down
```

## Porty/Envy

Dla uproszczenia porty zostały ustawione na sztywno w docker-compose.yaml:
- Phoenix API: 4000
    - zmiana portu w docker-compose.yaml wymusza zmianę `PHOENIX_API_URL` w pliku .env w symfony_app
- Symfony App: 8000
- PostgreSQL: 5432
    - zmiana portu w docker-compose.yaml wymusza zmianę `DATABASE_URL` dla kontenera Phoenix API

Zmiany w plikach .env wymagają przebudowania obrazów:
```bash
docker compose up --build -d
```

## Import danych

Aby importować dane, należy wysłać żądanie POST do endpointu `/import` w Phoenix API:
```bash
curl -v -X POST http://localhost:4000/import
```

Lub użyć przycisku "Import Users"  na panelu Symfony App. (Dodane poza wymaganiami dla wygody testowania)


## Phoenix API - backend

### URL: http://localhost:4000

### Endpointy
- `POST /import` - importuje 100 losowych użytkowników losując płeć a następnie losując imię i nazwisko spośród 100 nazwisk i imion dla tej płci.
    - Dane importowane są z plików CSV znajdujących się w katalogu `phoenix_api/priv`
        - `name_male.csv` - lista imion męskich
        - `name_female.csv` - lista imion żeńskich
        - `surname_male.csv` - lista nazwisk męskich
        - `surname_female.csv` - lista nazwisk żeńskich
- `GET /users` - pobiera listę użytkowników
- `GET /users/{id}` - pobiera dane użytkownika o podanym id
- `DELETE /users/{id}` - usuwa użytkownika o podanym id
- `PUT /users/{id}` - aktualizuje użytkownika o podanym id

## Symfony App - frontend

Aplikacja Symfony App pozwala na zarządzanie użytkownikami graficznym panelem użytkownika bedąc proxy do Phoenix API.

### URL: http://localhost:8000