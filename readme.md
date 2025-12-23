# Snugdesign

Jednoduchá ukázková aplikace postavená na **Nette 3.2**, běžící v **Dockeru**.

---

## Požadavky

- Docker
- Docker Compose

---

## Instalace

1. Naklonovat z repo:
   ```bash
   git clone https://github.com/dombym/snugdesign.git
   cd snugdesign

2. Spustit docker commands:
   ```bash
   docker compose build
   docker compose up -d
   docker compose exec app composer install

4. aplikace běží na http://localhost:8085
