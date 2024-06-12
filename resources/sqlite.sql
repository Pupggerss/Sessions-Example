-- #! sqlite

-- #{ init
-- # { players
CREATE TABLE IF NOT EXISTS players
(
    xuid     TEXT PRIMARY KEY,
    username TEXT,
    money    INTEGER DEFAULT 0
);
-- # }
-- # }

-- #{ add
-- # { player
-- # :xuid string
-- # :username string
-- # :money int
INSERT OR IGNORE INTO players(xuid, username, money)
VALUES (:xuid,
        :username,
        :money);
-- # }
-- # }

-- #{ get
-- # { money
-- # :xuid string
SELECT money
FROM players
WHERE xuid = :xuid;
-- # }
-- #  }

-- # { update
-- # { money
-- # :xuid string
-- # :money intUPDATE players SET money = :money
WHERE xuid = :xuid;
-- # }
-- # }