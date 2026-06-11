DROP TABLE IF EXISTS pokemon_moves;
DROP TABLE IF EXISTS move;
DROP TABLE IF EXISTS pokemon;

-- =========================
-- POKEMON
-- =========================

CREATE TABLE pokemon (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    type VARCHAR(50) NOT NULL,
    hp INT NOT NULL,
    attack INT NOT NULL,
    defense INT NOT NULL,
    level INT NOT NULL,
    PRIMARY KEY (id)
);

INSERT INTO pokemon (name, type, level, hp, attack, defense) VALUES
('Charmander', 'fire', 5, 39, 52, 43),
('Squirtle', 'water', 5, 44, 48, 65),
('Bulbasaur', 'grass', 5, 45, 49, 49),
('Pikachu', 'electric', 5, 35, 55, 40);

-- =========================
-- MOVES
-- =========================
CREATE TABLE move (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    type VARCHAR(50) NOT NULL,
    power INT NOT NULL,
    PRIMARY KEY (id)
);

INSERT INTO move (name, type, power) VALUES
('Ember', 'fire', 40),
('Flamethrower', 'fire', 90),
('Water Gun', 'water', 40),
('Hydro Pump', 'water', 110),
('Vine Whip', 'grass', 45),
('Razor Leaf', 'grass', 55),
('Thunder Shock', 'electric', 40),
('Thunderbolt', 'electric', 90);

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