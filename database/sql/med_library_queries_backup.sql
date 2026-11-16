CERATE DATABASE med_library;

CREATE EXTENSION IF NOT EXISTS pg_trgm;

CREATE TABLE authors(
	id SERIAL PRIMARY KEY,
	name VARCHAR(100) NOT NULL,
	last_name VARCHAR(100) NOT NULL,
	birthdate DATE,
	nationality VARCHAR(50),
	biography text
);

CREATE INDEX IF NOT EXISTS authors_full_search_trgm_idx 
ON authors USING GIN (
    (COALESCE(name, '') || ' ' || COALESCE(last_name, '') || ' ' || COALESCE(nationality, '')) gin_trgm_ops
);

CREATE TABLE books(
	id SERIAL PRIMARY KEY,
	title VARCHAR(255) NOT NULL,
	slug VARCHAR(255) unique,
	isbn VARCHAR(13) unique NOT NULL,
	publication_year smallint CHECK(publication_year >= 0),
	page_count smallint CHECK (page_count >= 0),
	description text,
	stock smallint DEFAULT 0 CHECK (stock >= 0),
	available_stock smallint DEFAULT stock CHECK (available_stock >= 0 and available_stock <= stock),
	created_at TIMESTAMP NOT NULL,
	updated_at TIMESTAMP DEFAULT null,
	deleted_at TIMESTAMP NULL
);

CREATE INDEX idx_books_title_year ON books (title, publication_year);
CREATE UNIQUE INDEX books_slug_unique ON books (slug) WHERE deleted_at IS NULL;

CREATE TABLE book_author(
	book_id INTEGER NOT NULL,
	author_id INTEGER NOT NULL,
	author_order INTEGER NOT NULL DEFAULT 1,
	PRIMARY KEY (book_id, author_id),
	
	CONSTRAINT fk_ba_books
		FOREIGN KEY (book_id)
		REFERENCES books(id)
		ON DELETE CASCADE,
		
	CONSTRAINT fk_ba_authors
		FOREIGN KEY (author_id)
		REFERENCES authors(id)
		ON DELETE RESTRICT
);

CREATE TABLE users(
	id SERIAL PRIMARY KEY,
	name VARCHAR(100) NOT NULL,
	last_name VARCHAR(100) NOT NULL,
	email VARCHAR(250) unique NOT NULL,
	phone_number VARCHAR(20) CHECK (phone_number ~ '^\+[1-9][0-9]{6,14}$'),
	created_at TIMESTAMP NOT NULL,
	updated_at TIMESTAMP DEFAULT null,
	is_active boolean DEFAULT true 
);

CREATE TABLE loans(
	id SERIAL PRIMARY KEY,
	user_id INTEGER NOT NULL,
	book_id INTEGER NOT NULL,
	borrowed_at TIMESTAMP NOT NULL DEFAULT current_timestamp,
	estimated_return_date DATE NOT NULL,
	return_date DATE,
	status VARCHAR(15) NOT NULL DEFAULT 'active',
	
	
	CONSTRAINT fk_users_loans
		FOREIGN KEY (user_id)
		REFERENCES users(id)
		ON DELETE RESTRICT,
		
	CONSTRAINT fk_books_loans
		FOREIGN KEY (book_id)
		REFERENCES books(id)
		ON DELETE RESTRICT,
		
	CONSTRAINT chk_return_date
		CHECK (return_date >= borrowed_at::DATE),
		
	CONSTRAINT chk_estimated_date
		CHECK (estimated_return_date >= borrowed_at::DATE),
		
	CONSTRAINT chk_status_values CHECK (status IN ('active', 'returned', 'overdue', 'lost'))
);





