CREATE TABLE IF NOT EXISTS sessions (
    sessid varchar(64) NOT NULL,
    user_id int NOT NULL,
    expiry datetime NOT NULL,
    PRIMARY KEY (sessid)
);