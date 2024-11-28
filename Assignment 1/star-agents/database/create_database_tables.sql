-- Create the 'Agent' table
CREATE TABLE IF NOT EXISTS Agent (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    first_name TEXT NOT NULL,
    surname TEXT NOT NULL,
    position TEXT,
    biography TEXT,
    phone TEXT,
    email TEXT UNIQUE,
    agency_id INTEGER NOT NULL,
    FOREIGN KEY (agency_id) REFERENCES Agency(id) ON DELETE CASCADE
);

-- Create the 'Agency' table
CREATE TABLE IF NOT EXISTS Agency (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    agency_name TEXT NOT NULL UNIQUE,
    agency_address TEXT
);

-- Create the 'Rating' table with auto timestamp and rating constraint
CREATE TABLE IF NOT EXISTS Rating (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    reviewer_name TEXT NOT NULL,
    ip_address TEXT NOT NULL,
    rating INTEGER NOT NULL CHECK (rating BETWEEN 1 AND 5),
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    review_heading TEXT,
    review TEXT,
    agent_id INTEGER NOT NULL,
    flagged_review INTEGER DEFAULT 0 CHECK (flagged_review IN (0, 1)),
    FOREIGN KEY (agent_id) REFERENCES Agent(id) ON DELETE CASCADE
);

-- Create the 'Flagged_IP' table with auto timestamp
CREATE TABLE IF NOT EXISTS Flagged_IP (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    ip_address TEXT NOT NULL,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create the 'Flagged_Username' table with auto timestamp
CREATE TABLE IF NOT EXISTS Flagged_Username (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create initial data to use in the website
INSERT INTO Agency (agency_name, agency_address) 
VALUES ('Coronis Realty', '1 Test Street, Exampleville');

INSERT INTO Agency (agency_name, agency_address) 
VALUES ('Ray White', '2 Test Street, Exampleville');

INSERT INTO Agent (first_name, surname, position, biography, phone, email, agency_id) 
VALUES ('John', 'Doe', 'Sales Consultant', 'Lorem ipsum dolor sit amet, ea meis numquam repudiare pro. His ei audire omnesque consulatu, has modo volutpat consulatu in, te fugit aeterno definitiones vix. Vis ullum numquam at, et adhuc atqui congue pri. No tale copiosae mea. Te principes corrumpit sit, at modus accusamus quaerendum nec. Nam eu aperiam mnesarchum, alii apeirian appellantur ea per. An sea eius utamur laboramus. Mel malorum detraxit adipisci an, rebum tibique consequat in eum. Eum at aliquando efficiantur reprehendunt, an accusam senserit dissentias sit, at alterum dolorum sadipscing ius. Dicit feugiat legimus eos ea, mel dolorem accommodare signiferumque ei, dicit reprimique usu et.', '0412 345 678', 'john.doe@coronis.com.au', 1);

INSERT INTO Agent (first_name, surname, position, biography, phone, email, agency_id) 
VALUES ('Jane', 'Smith', 'Property Manager', 'Lorem ipsum dolor sit amet, ea meis numquam repudiare pro. His ei audire omnesque consulatu, has modo volutpat consulatu in, te fugit aeterno definitiones vix. Vis ullum numquam at, et adhuc atqui congue pri. No tale copiosae mea. Te principes corrumpit sit, at modus accusamus quaerendum nec. Nam eu aperiam mnesarchum, alii apeirian appellantur ea per. An sea eius utamur laboramus. Mel malorum detraxit adipisci an, rebum tibique consequat in eum. Eum at aliquando efficiantur reprehendunt, an accusam senserit dissentias sit, at alterum dolorum sadipscing ius. Dicit feugiat legimus eos ea, mel dolorem accommodare signiferumque ei, dicit reprimique usu et.', '0423 456 789', 'jane.smith@raywhite.com.au', 2);

INSERT INTO Agent (first_name, surname, position, biography, phone, email, agency_id) 
VALUES ('Sam', 'Warwick', 'Junior Sales Consultant', 'Lorem ipsum dolor sit amet, ea meis numquam repudiare pro. His ei audire omnesque consulatu, has modo volutpat consulatu in, te fugit aeterno definitiones vix. Vis ullum numquam at, et adhuc atqui congue pri. No tale copiosae mea. Te principes corrumpit sit, at modus accusamus quaerendum nec. Nam eu aperiam mnesarchum, alii apeirian appellantur ea per. An sea eius utamur laboramus. Mel malorum detraxit adipisci an, rebum tibique consequat in eum. Eum at aliquando efficiantur reprehendunt, an accusam senserit dissentias sit, at alterum dolorum sadipscing ius. Dicit feugiat legimus eos ea, mel dolorem accommodare signiferumque ei, dicit reprimique usu et.', '0443 766 129', 'sam.warwick@raywhite.com.au', 2);

INSERT INTO Rating (reviewer_name, ip_address, rating, review_heading, review, agent_id)
VALUES ('Alice Brown', '192.168.0.1', 5, 'Outstanding Service', 'John was extremely helpful and provided excellent service.', 1);

INSERT INTO Rating (reviewer_name, ip_address, rating, review_heading, review, agent_id)
VALUES ('Bob Smith', '192.168.0.2', 4, 'Great Experience', 'John was great to work with, very professional.', 1);

INSERT INTO Rating (reviewer_name, ip_address, rating, review_heading, review, agent_id)
VALUES ('Charlie Green', '192.168.0.3', 3, 'Good Overall', 'John was helpful but there were a few delays.', 1);

INSERT INTO Rating (reviewer_name, ip_address, rating, review_heading, review, agent_id)
VALUES ('Dana White', '192.168.0.4', 2, 'Could be Better', 'John was friendly but didn''t meet all our expectations.', 1);

INSERT INTO Rating (reviewer_name, ip_address, rating, review_heading, review, agent_id)
VALUES ('Eve Black', '192.168.0.5', 1, 'Very Disappointing', 'Unfortunately, John didn''t provide the service we hoped for.', 1);

INSERT INTO Rating (reviewer_name, ip_address, rating, review_heading, review, agent_id)
VALUES ('Gina Brown', '192.168.0.7', 4, 'Very Professional', 'Jane handled everything with care and professionalism.', 2);

INSERT INTO Rating (reviewer_name, ip_address, rating, review_heading, review, agent_id)
VALUES ('Harry Red', '192.168.0.8', 3, 'Good Service', 'Jane was good but there were a few issues with communication.', 2);

INSERT INTO Rating (reviewer_name, ip_address, rating, review_heading, review, agent_id)
VALUES ('Isla Green', '192.168.0.9', 2, 'Average Experience', 'Jane could have been more responsive to our questions.', 2);

INSERT INTO Rating (reviewer_name, ip_address, rating, review_heading, review, agent_id)
VALUES ('Jake Blue', '192.168.0.10', 1, 'Not Satisfied', 'We were not happy with Jane''s service overall.', 2);

INSERT INTO Rating (reviewer_name, ip_address, rating, review_heading, review, agent_id)
VALUES ('Mia Red', '192.168.0.13', 3, 'Satisfactory', 'Sam did an okay job but there was room for improvement.', 3);

INSERT INTO Rating (reviewer_name, ip_address, rating, review_heading, review, agent_id)
VALUES ('Noah Green', '192.168.0.14', 2, 'Could Be Better', 'Sam wasn''t as responsive as we would have liked.', 3);

INSERT INTO Rating (reviewer_name, ip_address, rating, review_heading, review, agent_id)
VALUES ('Olivia Blue', '192.168.0.15', 1, 'Not Impressed', 'We were not happy with Sam''s overall performance.', 3);






