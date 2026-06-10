# Pokémon Battle API

API REST en PHP nativo que simula combates entre Pokémon, incluyendo cálculo de daño, gestión de Pokémon y movimientos, y sistema de batalla.


# Stack utilizado

- PHP 8.x
- MySQL
- PDO
- PHPUnit 10
- Docker (opcional)
- Arquitectura tipo hexagonal ligera / capas


# Arquitectura del proyecto

El proyecto sigue una separación por capas:

src/
    Domain/ (modelo de negocio implícito en arrays)
    Application/
        Service/
            DamageService.php
    Infrastructure/
        Http/Controller/
        Persistence/ (Repositorios PDO)
public/
tests/

Responsabilidades:

- Controller: entrada HTTP y salida JSON
- Repository: acceso a base de datos (PDO)
- Service: lógica de negocio (cálculo de daño)
- DB: relaciones Pokémon - movimientos


# Sistema de batalla

La lógica de combate se basa en la siguiente fórmula:

Daño =
[( (2 * Nivel / 5 + 2) * Ataque * Poder ) / Defensa ] / 50
* Efectividad * Random / 100

Factores:
- Nivel del atacante
- Ataque del Pokémon atacante
- Defensa del defensor
- Poder del movimiento
- Efectividad entre tipos (0 / 0.5 / 1 / 2)
- Factor aleatorio


# Efectividad de tipos

La efectividad se calcula mediante una matriz de tipos (18x18), donde:

- Filas: tipo del movimiento
- Columnas: tipo del Pokémon defensor
- Valores:
  - 0: no afecta
  - 0.5: poco eficaz
  - 1: normal
  - 2: súper eficaz


# Base de datos

Tablas principales:

pokemon:
- id
- name
- type
- hp
- attack
- defense
- level

move:
- id
- name
- type
- power

pokemon_moves:
- pokemon_id
- move_id


# Endpoints

Pokémon:
- GET /pokemon
- GET /pokemon/{id}
- POST /pokemon
- PUT /pokemon/{id}
- DELETE /pokemon/{id}

Moves:
- GET /moves
- GET /moves/{id}
- POST /moves
- PUT /moves/{id}
- DELETE /moves/{id}

Battle:
- POST /battle/attack

Payload:

{
  "attacker_id": 1,
  "defender_id": 2,
  "move_id": 1
}

Response:

{
  "damage": 12,
  "defender_hp": 88,
  "ko": false
}


# Request Examples

POST /pokemon

{
  "name": "Pikachu",
  "type": "electric",
  "hp": 100,
  "attack": 55,
  "defense": 40,
  "level": 10
}

PUT /pokemon/{id}

{
  "name": "Pikachu",
  "type": "electric",
  "hp": 120,
  "attack": 60,
  "defense": 45,
  "level": 12
}

POST /move

{
  "name": "Thunderbolt",
  "type": "electric",
  "power": 90
}

POST /battle/attack

{
  "attacker_id": 1,
  "defender_id": 2,
  "move_id": 3
}


# Testing

Se utilizan PHPUnit 10 con tests unitarios básicos:

- Test de cálculo de daño (DamageService)
- Test de endpoint de batalla (BattleController)

Ejecutar tests:

vendor\bin\phpunit

Hay algunos warnings que generan ruido. Queda pendiente resolverlos.


# Decisiones técnicas

- Se usa PDO directamente para simplicidad y control total
- No se ha usado framework para mantener la prueba ligera
- La lógica de negocio se separa en DamageService
- Se simulan repositorios en tests mediante mocks
- Arquitectura en capas para mantener escalabilidad


# Uso de IA

Se ha utilizado IA como apoyo para:

- Aceleración del desarrollo de código repetitivo
- Revisión de errores de configuración (PHPUnit / Docker)
- Mejora de estructura de tests
- Debug de integración de endpoints

Toda la lógica de negocio ha sido implementada y validada manualmente.


# Mejoras futuras

- Migración a framework (Laravel / Symfony)
- Tipado estricto con DTOs
- Mejor gestión de dominio (Entity layer real)
- Validación de requests
- Autenticación API
- Sistema de turnos en batalla
- Documentación de código


# Ejecución del proyecto

Docker:
docker-compose up -d

Local:
php -S localhost:8000 -t public (si no usas docker, si lo usas se ejecuta automáticamente)

La configuración de entorno se gestiona directamente en docker-compose.yml.
No se utiliza archivo .env para simplificar la ejecución del proyecto en local.