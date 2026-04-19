CREATE TABLE IF NOT EXISTS characters (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    avatar_uuid CHAR(36) NOT NULL,
    name VARCHAR(128) NOT NULL,
    description TEXT NOT NULL,
    text_color VARCHAR(16) NOT NULL DEFAULT '#FFFFFF',
    gender_tag VARCHAR(64) NOT NULL DEFAULT '',
    consent_tag VARCHAR(64) NOT NULL DEFAULT '',
    stat_weights_json JSON NULL,
    imported_from_legacy TINYINT(1) NOT NULL DEFAULT 0,
    last_loaded_at DATETIME NOT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    INDEX idx_char_avatar_loaded (avatar_uuid, last_loaded_at)
);

CREATE TABLE IF NOT EXISTS temporary_attachments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    avatar_uuid CHAR(36) NOT NULL,
    slot_name VARCHAR(64) NOT NULL,
    object_uuid CHAR(36) NULL,
    attached TINYINT(1) NOT NULL DEFAULT 0,
    status VARCHAR(32) NOT NULL DEFAULT 'pending',
    last_seen_at DATETIME NOT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    UNIQUE KEY uq_avatar_slot (avatar_uuid, slot_name),
    INDEX idx_attach_status_seen (status, last_seen_at)
);

CREATE TABLE IF NOT EXISTS registered_objects (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    object_uuid CHAR(36) NOT NULL,
    owner_avatar_uuid CHAR(36) NOT NULL,
    object_type VARCHAR(64) NOT NULL,
    x DOUBLE NOT NULL,
    y DOUBLE NOT NULL,
    z DOUBLE NOT NULL,
    min_x DOUBLE NOT NULL,
    max_x DOUBLE NOT NULL,
    min_y DOUBLE NOT NULL,
    max_y DOUBLE NOT NULL,
    min_z DOUBLE NOT NULL,
    max_z DOUBLE NOT NULL,
    linkset_id VARCHAR(64) NULL,
    metadata_json JSON NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    UNIQUE KEY uq_object_uuid (object_uuid)
);

CREATE TABLE IF NOT EXISTS object_commands (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    object_uuid CHAR(36) NOT NULL,
    command_name VARCHAR(64) NOT NULL,
    payload_json JSON NULL,
    status VARCHAR(32) NOT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    INDEX idx_object_command_status (object_uuid, status)
);

CREATE TABLE IF NOT EXISTS environment_zones (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    zone_name VARCHAR(128) NOT NULL,
    min_x DOUBLE NOT NULL,
    max_x DOUBLE NOT NULL,
    min_y DOUBLE NOT NULL,
    max_y DOUBLE NOT NULL,
    min_z DOUBLE NOT NULL,
    max_z DOUBLE NOT NULL,
    settings_json JSON NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    UNIQUE KEY uq_zone_name (zone_name)
);
