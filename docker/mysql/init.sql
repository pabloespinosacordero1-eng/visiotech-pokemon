DROP TABLE IF EXISTS pokemon_moves;
DROP TABLE IF EXISTS move;
DROP TABLE IF EXISTS pokemon;

-- =========================
-- POKEMON
-- =========================
CREATE TABLE pokemon (
  id INT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  type VARCHAR(50) NOT NULL,
  level INT NOT NULL,
  hp INT NOT NULL,
  attack INT NOT NULL,
  defense INT NOT NULL
);

INSERT INTO pokemon (id, name, type, level, hp, attack, defense) VALUES
(1, 'Charmander', 'fire', 5, 39, 52, 43),
(2, 'Squirtle', 'water', 5, 44, 48, 65),
(3, 'Bulbasaur', 'grass', 5, 45, 49, 49),
(4, 'Pikachu', 'electric', 5, 35, 55, 40);

-- =========================
-- MOVES
-- =========================
CREATE TABLE move (
  id INT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  type VARCHAR(50) NOT NULL,
  power INT NOT NULL
);

INSERT INTO move (id, name, type, power) VALUES
(1, 'Ember', 'fire', 40),
(2, 'Flamethrower', 'fire', 90),
(3, 'Water Gun', 'water', 40),
(4, 'Hydro Pump', 'water', 110),
(5, 'Vine Whip', 'grass', 45),
(6, 'Razor Leaf', 'grass', 55),
(7, 'Thunder Shock', 'electric', 40),
(8, 'Thunderbolt', 'electric', 90);

-- =========================
-- RELATION POKEMON-MOVES
-- =========================
CREATE TABLE pokemon_moves (
  pokemon_id INT NOT NULL,
  move_id INT NOT NULL,
  PRIMARY KEY (pokemon_id, move_id)
);

INSERT INTO pokemon_moves (pokemon_id, move_id) VALUES
(1, 1),
(1, 2),
(2, 3),
(2, 4),
(3, 5),
(3, 6),
(4, 7),
(4, 8);