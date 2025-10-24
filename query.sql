CREATE TABLE documents
(
 id INT
 AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR
 (255),
  description TEXT,
  type VARCHAR
 (100),
  semester INT,
  filename VARCHAR
 (255),
  uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);