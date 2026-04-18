CREATE TABLE family (
    fam_id INT PRIMARY KEY AUTO_INCREMENT,
    fam_name VARCHAR(100) NOT NULL,
    member_count INT NOT NULL DEFAULT 0
);

CREATE TABLE member (
    net_id VARCHAR(10) PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    year ENUM('freshman', 'sophomore', 'junior', 'senior', 'graduate') NOT NULL,
    major VARCHAR(100) DEFAULT NULL,
    join_date DATE DEFAULT (CURRENT_DATE),
    fam_id INT,
    FOREIGN KEY (fam_id) REFERENCES family(fam_id)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);

CREATE TABLE event (
    event_id INT PRIMARY KEY AUTO_INCREMENT,
    event_name VARCHAR(100) NOT NULL,
    description TEXT DEFAULT NULL,
    location VARCHAR(200) DEFAULT NULL,
    event_date DATE DEFAULT NULL,
    start_time TIME DEFAULT NULL,
    capacity INT DEFAULT NULL
);

CREATE TABLE fam_head (
    fam_head_id INT PRIMARY KEY AUTO_INCREMENT,
    start_date DATE NOT NULL DEFAULT (CURRENT_DATE),
    end_date DATE DEFAULT NULL,
    net_id VARCHAR(10) NOT NULL,
    fam_id INT NOT NULL,
    FOREIGN KEY (net_id) REFERENCES member(net_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (fam_id) REFERENCES family(fam_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE officer (
    officer_id INT PRIMARY KEY AUTO_INCREMENT,
    position VARCHAR(100) NOT NULL,
    start_date DATE NOT NULL DEFAULT (CURRENT_DATE),
    end_date DATE DEFAULT NULL,
    net_id VARCHAR(10) NOT NULL,
    password VARCHAR(255) NOT NULL,
    FOREIGN KEY (net_id) REFERENCES member(net_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE attendance (
    event_id INT NOT NULL,
    net_id VARCHAR(10) NOT NULL,
    PRIMARY KEY (event_id, net_id),
    sign_up_date DATE NOT NULL DEFAULT (CURRENT_DATE),
    attended BOOLEAN NOT NULL DEFAULT FALSE,
    FOREIGN KEY (event_id) REFERENCES event(event_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (net_id) REFERENCES member(net_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE organizes (
    event_id INT NOT NULL,
    fam_head_id INT NOT NULL,
    role VARCHAR(100) DEFAULT NULL,
    FOREIGN KEY (event_id) REFERENCES event(event_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (fam_head_id) REFERENCES fam_head(fam_head_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);


