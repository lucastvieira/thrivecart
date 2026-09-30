# ThriveCart

A small checkout stack: a plain PHP API and a React client. The catalog, offers, and delivery rules live in static JSON under `server/data`.

## Run with Docker

Docker Compose builds the API (PHP 8.5, Apache, Xdebug) and the client (Node 22, pnpm, Vite).

`server/vendor` is gitignored, and Compose mounts `server/` into the container, so generate the Composer autoload once before the first start:

```bash
docker run --rm -v ./server:/app -w /app composer:2 dump-autoload
docker compose up --build
```

| Service | URL |
|---------|-----|
| Client  | http://localhost:3000 |
| API     | http://localhost:8080 |

Stop with `docker compose down`. Source under `server/` and `client/` is mounted, so most edits show up without rebuilding. Xdebug is on in the API image; the VS Code config "Listen for Xdebug" in `.vscode/launch.json` attaches on port 9003.

## Run without Docker

Use two terminals. The API needs PHP 8.5 and Composer. The client needs Node 22 and pnpm 9.15.9 (`packageManager` in `client/package.json`; `corepack enable` is enough on Node 22).

**API** — from `server/`:

```bash
composer dump-autoload
php -S localhost:8080 -t public public/index.php
```

`public/index.php` is the router script, so every path is handled by the front controller. That matches the Apache `FallbackResource` used in Docker. The API is at http://localhost:8080.

**Client** — from `client/`:

```bash
cp .env.example .env
pnpm install
pnpm dev
```

Vite reads `VITE_API_URL` from `.env` (`http://localhost:8080`). The client is at http://localhost:5173. Docker publishes that same dev server on port 3000; locally it stays on 5173.

## Tests

From `server/`, with PHP and Composer:

```bash
composer install
composer test
```

The API image does not include Composer. To run the same suite in the Composer image:

```bash
docker run --rm -v ./server:/app -w /app composer:2 install
docker run --rm -v ./server:/app -w /app --entrypoint vendor/bin/phpunit composer:2
```

## How it works

```
client (React + Vite)  →  HTTP JSON  →  server (PHP + Apache)
                                              ↓
                                        server/data/*.json
```

**Server.** `server/public/index.php` is the only entry point. It prepares the request (CORS, JSON headers), registers routes on a small in-house router, and dispatches. App code lives in `server/lib` and is autoloaded by Composer (`Thrivecart\Server\`). There is no PHP framework and no database.

**Catalog.** Products, offers, and delivery tiers are JSON files. `Catalog` reads each file the first time a handler asks for it and keeps the decoded result for the rest of that request.

**Client.** Vite serves the React app. API calls go through an Axios client pointed at `VITE_API_URL`.

## Assumptions and trade-offs

### Assumptions

These are the domain and architecture choices this project depends on:

1. **Static catalog.** Products, offers, and delivery rules are fixed JSON files. `Catalog` reads a file on first use and keeps it for that request only.

2. **Prices are integers in the smallest currency unit** (cents). Example: Red Widget is `3295` → $32.95. Totals, discounts, and delivery costs use the same unit.

3. **One offer type applies to one product code.** The current rule is "buy one, get the second half price" on `R01`. An offer's `product` field is a single code.

4. **Delivery is tiered by order total.** Rules in `delivery.json` use an `under` threshold. Cost is chosen from the order subtotal after discounts. The bands are under $50 ($4.95), under $90 ($2.95), and free at $90 and above.

5. **No framework on the server.** Routing, HTTP setup, JSON responses, and catalog loading are small classes under `server/lib`. Offer and shipping math are static methods on the service classes.

6. **Client and API are separate origins.** The browser talks to the API on another port. CORS is handled by the PHP app so the React client can call it during development.

### Trade-offs

1. **The catalog is the JSON files.** There is no database and no admin UI. Changing a price or a rule means editing `server/data`.

2. **Half price rounds the discount up.** The second-item discount is `ceil(price / 2)`, so an odd cent never becomes a float. Red Widget is 3295 cents; the discount is 1648 cents ($16.48), half a cent more than exact half price, in the customer's favor.

3. **A discount can raise the delivery cost.** Shipping is chosen from the subtotal after the offer. Three Red Widgets are $98.85 before the offer, which is free delivery. After one half-price pair the subtotal is $82.37, and delivery becomes $2.95.

4. **The open-ended delivery row is a record of the rule, and the code falls through to free delivery.** `ShippingService` returns the first tier where `total < under`. The last row has `under: null`. PHP treats that `null` as `0`, so a normal total never matches the row. The method then returns `0`. That lines up with the row's `cost: 0`. A different cost on that row would still be ignored.

5. **The server prices the basket from the catalog.** `CalculateBasketService` multiplies the catalog price by the quantity on the first payload line for that product code. The price on the request is unused. A code missing from the catalog adds nothing. A second line for the same code is skipped, so quantity belongs on one line.

6. **This stack is for local development.** The API image runs Xdebug, Compose bind-mounts the source, and the client container is the Vite dev server. CORS allows any origin. An uncaught exception responds with the message and the stack trace. The API calculates a total for the basket in the request. Accounts, stored orders, and payments are out of scope.

7. **A new offer is a code change.** There is no dependency injection and no rules engine. Pricing rules are the static calls in `CartController`. Adding an offer type means a new service and a new call there, which keeps the path from request to total short and visible.

8. **Tests were added after the checkout worked.** Writing them during the challenge would have taken time away from the basket rules and the client. The server is small enough to check by hand: put the example baskets in the cart and read the shipping and total. PHPUnit was added later, on the same static services `CartController` calls, to keep those totals, the red-widget offer, and the delivery boundaries at $50 and $90.
