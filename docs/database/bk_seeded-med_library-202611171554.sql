--
-- PostgreSQL database cluster dump
--

-- Started on 2026-11-17 15:54:20

\restrict 8aLkCpDQ4LqlpJgrFvrrUa7f5BEXtZCLaHv8X7bKw9ozNJhMfZNs6eK9KqgcHtT

SET default_transaction_read_only = off;

SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;

--
-- Roles
--

CREATE ROLE postgres;
ALTER ROLE postgres WITH SUPERUSER INHERIT CREATEROLE CREATEDB LOGIN REPLICATION BYPASSRLS;

--
-- User Configurations
--








\unrestrict 8aLkCpDQ4LqlpJgrFvrrUa7f5BEXtZCLaHv8X7bKw9ozNJhMfZNs6eK9KqgcHtT

--
-- Databases
--

--
-- Database "template1" dump
--

\connect template1

--
-- PostgreSQL database dump
--

\restrict bV0lx8FAgmOWs94VQlzHknsBNlqsE3NEeVC57mNy2SJUA02iq9GhIBq2hhj7jAT

-- Dumped from database version 18.6
-- Dumped by pg_dump version 18.6

-- Started on 2026-11-17 15:54:20

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

-- Completed on 2026-11-17 15:54:21

--
-- PostgreSQL database dump complete
--

\unrestrict bV0lx8FAgmOWs94VQlzHknsBNlqsE3NEeVC57mNy2SJUA02iq9GhIBq2hhj7jAT

--
-- Database "med_library" dump
--

--
-- PostgreSQL database dump
--

\restrict 1elGJ98b3TuycHfP8Tfw0GMh3XScgnWjTd5juPMHBhRaYJxZUFufBmUe0z8IyBG

-- Dumped from database version 18.6
-- Dumped by pg_dump version 18.6

-- Started on 2026-11-17 15:54:21

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 5168 (class 1262 OID 16934)
-- Name: med_library; Type: DATABASE; Schema: -; Owner: postgres
--

CREATE DATABASE med_library WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE_PROVIDER = libc LOCALE = 'Spanish_Spain.1252';


ALTER DATABASE med_library OWNER TO postgres;

\unrestrict 1elGJ98b3TuycHfP8Tfw0GMh3XScgnWjTd5juPMHBhRaYJxZUFufBmUe0z8IyBG
\connect med_library
\restrict 1elGJ98b3TuycHfP8Tfw0GMh3XScgnWjTd5juPMHBhRaYJxZUFufBmUe0z8IyBG

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 2 (class 3079 OID 17073)
-- Name: pg_trgm; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS pg_trgm WITH SCHEMA public;


--
-- TOC entry 5169 (class 0 OID 0)
-- Dependencies: 2
-- Name: EXTENSION pg_trgm; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION pg_trgm IS 'text similarity measurement and index searching based on trigrams';


SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 234 (class 1259 OID 19444)
-- Name: authors; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.authors (
    id bigint NOT NULL,
    name character varying(100) NOT NULL,
    last_name character varying(100) NOT NULL,
    birthdate date,
    nationality character varying(50),
    biography text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.authors OWNER TO postgres;

--
-- TOC entry 233 (class 1259 OID 19443)
-- Name: authors_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.authors_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.authors_id_seq OWNER TO postgres;

--
-- TOC entry 5170 (class 0 OID 0)
-- Dependencies: 233
-- Name: authors_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.authors_id_seq OWNED BY public.authors.id;


--
-- TOC entry 237 (class 1259 OID 19481)
-- Name: book_author; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.book_author (
    book_id bigint NOT NULL,
    author_id bigint NOT NULL,
    author_order smallint DEFAULT '1'::smallint NOT NULL
);


ALTER TABLE public.book_author OWNER TO postgres;

--
-- TOC entry 236 (class 1259 OID 19457)
-- Name: books; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.books (
    id bigint NOT NULL,
    title character varying(255) NOT NULL,
    slug character varying(255) NOT NULL,
    isbn character varying(13) NOT NULL,
    publication_year smallint,
    page_count integer,
    description text,
    stock integer DEFAULT 0 NOT NULL,
    available_stock integer DEFAULT 0 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    CONSTRAINT chk_books_available_stock CHECK (((available_stock >= 0) AND (available_stock <= stock))),
    CONSTRAINT chk_books_stock CHECK ((stock >= 0))
);


ALTER TABLE public.books OWNER TO postgres;

--
-- TOC entry 235 (class 1259 OID 19456)
-- Name: books_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.books_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.books_id_seq OWNER TO postgres;

--
-- TOC entry 5171 (class 0 OID 0)
-- Dependencies: 235
-- Name: books_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.books_id_seq OWNED BY public.books.id;


--
-- TOC entry 222 (class 1259 OID 19327)
-- Name: cache; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration bigint NOT NULL
);


ALTER TABLE public.cache OWNER TO postgres;

--
-- TOC entry 223 (class 1259 OID 19338)
-- Name: cache_locks; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cache_locks (
    key character varying(255) NOT NULL,
    owner character varying(255) NOT NULL,
    expiration bigint NOT NULL
);


ALTER TABLE public.cache_locks OWNER TO postgres;

--
-- TOC entry 228 (class 1259 OID 19380)
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection character varying(255) NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.failed_jobs OWNER TO postgres;

--
-- TOC entry 227 (class 1259 OID 19379)
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.failed_jobs_id_seq OWNER TO postgres;

--
-- TOC entry 5172 (class 0 OID 0)
-- Dependencies: 227
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- TOC entry 226 (class 1259 OID 19365)
-- Name: job_batches; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.job_batches (
    id character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    total_jobs integer NOT NULL,
    pending_jobs integer NOT NULL,
    failed_jobs integer NOT NULL,
    failed_job_ids text NOT NULL,
    options text,
    cancelled_at integer,
    created_at integer NOT NULL,
    finished_at integer
);


ALTER TABLE public.job_batches OWNER TO postgres;

--
-- TOC entry 225 (class 1259 OID 19350)
-- Name: jobs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.jobs (
    id bigint NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    attempts smallint NOT NULL,
    reserved_at integer,
    available_at integer NOT NULL,
    created_at integer NOT NULL
);


ALTER TABLE public.jobs OWNER TO postgres;

--
-- TOC entry 224 (class 1259 OID 19349)
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.jobs_id_seq OWNER TO postgres;

--
-- TOC entry 5173 (class 0 OID 0)
-- Dependencies: 224
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;


--
-- TOC entry 239 (class 1259 OID 19501)
-- Name: loans; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.loans (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    book_id bigint NOT NULL,
    borrowed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    estimated_return_date date NOT NULL,
    return_date date,
    status character varying(20) DEFAULT 'active'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT chk_estimated_date CHECK ((estimated_return_date >= (borrowed_at)::date)),
    CONSTRAINT chk_return_date CHECK ((return_date >= (borrowed_at)::date)),
    CONSTRAINT chk_status_values CHECK (((status)::text = ANY ((ARRAY['active'::character varying, 'returned'::character varying, 'overdue'::character varying, 'lost'::character varying])::text[])))
);


ALTER TABLE public.loans OWNER TO postgres;

--
-- TOC entry 238 (class 1259 OID 19500)
-- Name: loans_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.loans_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.loans_id_seq OWNER TO postgres;

--
-- TOC entry 5174 (class 0 OID 0)
-- Dependencies: 238
-- Name: loans_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.loans_id_seq OWNED BY public.loans.id;


--
-- TOC entry 221 (class 1259 OID 19318)
-- Name: migrations; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE public.migrations OWNER TO postgres;

--
-- TOC entry 220 (class 1259 OID 19317)
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.migrations_id_seq OWNER TO postgres;

--
-- TOC entry 5175 (class 0 OID 0)
-- Dependencies: 220
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- TOC entry 231 (class 1259 OID 19422)
-- Name: password_reset_tokens; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


ALTER TABLE public.password_reset_tokens OWNER TO postgres;

--
-- TOC entry 241 (class 1259 OID 19529)
-- Name: personal_access_tokens; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.personal_access_tokens (
    id bigint NOT NULL,
    tokenable_type character varying(255) NOT NULL,
    tokenable_id bigint NOT NULL,
    name text NOT NULL,
    token character varying(64) NOT NULL,
    abilities text,
    last_used_at timestamp(0) without time zone,
    expires_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.personal_access_tokens OWNER TO postgres;

--
-- TOC entry 240 (class 1259 OID 19528)
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.personal_access_tokens_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.personal_access_tokens_id_seq OWNER TO postgres;

--
-- TOC entry 5176 (class 0 OID 0)
-- Dependencies: 240
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.personal_access_tokens_id_seq OWNED BY public.personal_access_tokens.id;


--
-- TOC entry 232 (class 1259 OID 19431)
-- Name: sessions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.sessions (
    id character varying(255) NOT NULL,
    user_id bigint,
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);


ALTER TABLE public.sessions OWNER TO postgres;

--
-- TOC entry 230 (class 1259 OID 19400)
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    last_name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    phone_number character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    is_active boolean DEFAULT true NOT NULL,
    password character varying(255),
    CONSTRAINT check_phone_number_format CHECK (((phone_number)::text ~ '^\+[1-9][0-9]{6,14}$'::text))
);


ALTER TABLE public.users OWNER TO postgres;

--
-- TOC entry 229 (class 1259 OID 19399)
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.users_id_seq OWNER TO postgres;

--
-- TOC entry 5177 (class 0 OID 0)
-- Dependencies: 229
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- TOC entry 4922 (class 2604 OID 19447)
-- Name: authors id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.authors ALTER COLUMN id SET DEFAULT nextval('public.authors_id_seq'::regclass);


--
-- TOC entry 4923 (class 2604 OID 19460)
-- Name: books id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.books ALTER COLUMN id SET DEFAULT nextval('public.books_id_seq'::regclass);


--
-- TOC entry 4918 (class 2604 OID 19383)
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- TOC entry 4917 (class 2604 OID 19353)
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- TOC entry 4927 (class 2604 OID 19504)
-- Name: loans id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.loans ALTER COLUMN id SET DEFAULT nextval('public.loans_id_seq'::regclass);


--
-- TOC entry 4916 (class 2604 OID 19321)
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- TOC entry 4930 (class 2604 OID 19532)
-- Name: personal_access_tokens id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.personal_access_tokens ALTER COLUMN id SET DEFAULT nextval('public.personal_access_tokens_id_seq'::regclass);


--
-- TOC entry 4920 (class 2604 OID 19403)
-- Name: users id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- TOC entry 5155 (class 0 OID 19444)
-- Dependencies: 234
-- Data for Name: authors; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.authors (id, name, last_name, birthdate, nationality, biography, created_at, updated_at) FROM stdin;
1	Dr. Roy Turner III	Ankunding	1986-09-26	Suriname	Iste sapiente id recusandae voluptates aut. Autem recusandae asperiores maxime numquam inventore perspiciatis. Provident consectetur nihil soluta nemo. Ea aliquam sed quis impedit quia distinctio vero sit.	\N	\N
2	Nicolette Grimes	Koelpin	2010-02-03	Serbia	Animi pariatur ut iste excepturi est. Odio dolor porro fugiat soluta quae voluptatem et. Voluptatem enim inventore dignissimos dolores similique cumque.	\N	\N
3	Kattie Skiles	Harris	2022-02-10	Tunisia	Sit quo eum eligendi saepe vel ea. Facere porro quis aut exercitationem. Ex deleniti ex perspiciatis voluptatem dolorem impedit adipisci sapiente. Ea necessitatibus sapiente mollitia atque laboriosam voluptas. Asperiores quos non ducimus quasi.	\N	\N
4	Mr. Deonte Runolfsson PhD	Jaskolski	1981-05-11	Solomon Islands	Ad sint est quibusdam illum aspernatur deserunt. Qui molestiae libero sed impedit odio nam. Autem ipsam non minima qui. Dolores cumque qui non doloribus in ipsa.	\N	\N
5	Miss Norma Hammes V	McLaughlin	2024-12-14	Barbados	Est consectetur et debitis fugiat repellat aspernatur. Rerum omnis atque omnis sequi commodi. Dolorem et enim quis cum accusamus voluptates.	\N	\N
6	Annabel Bosco DVM	O'Kon	2006-01-01	Kuwait	Dolor explicabo quae ab et omnis quisquam tenetur. Sint voluptas officiis debitis sit enim minima ducimus. Vel error nisi saepe delectus consequatur at eaque est. Est aliquam commodi et consequatur.	\N	\N
7	Carlo Champlin	Schinner	2014-10-01	Norfolk Island	Velit reiciendis ut ut ut perspiciatis tempora quidem. Necessitatibus corporis aut animi nulla quia nobis accusamus a.	\N	\N
8	Mrs. Clotilde Brown	Orn	1986-09-14	Colombia	Vel et consequatur quis eligendi ipsum sapiente aliquam. Porro quos sed quia. Qui nam ullam deleniti corrupti. Non iusto sint natus voluptate qui odit facilis.	\N	\N
9	Ms. Lauretta Prohaska	Bruen	2026-08-31	Azerbaijan	Ratione optio aut iure hic laborum aut assumenda. Qui magni est dolores aut. Quia qui quasi eligendi et ullam aut alias. Ducimus dolores et et excepturi.	\N	\N
10	Lilian Kemmer IV	Mohr	1980-12-22	French Polynesia	Vel voluptas rerum ut et architecto laboriosam. Vel enim voluptates cupiditate voluptatem ipsam aut. Praesentium expedita repellendus et ratione quae. Molestiae soluta dolorem odio unde.	\N	\N
\.


--
-- TOC entry 5158 (class 0 OID 19481)
-- Dependencies: 237
-- Data for Name: book_author; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.book_author (book_id, author_id, author_order) FROM stdin;
1	1	1
1	5	2
1	8	3
2	3	1
2	4	2
2	8	3
3	3	1
3	5	2
3	8	3
4	2	1
4	9	2
5	2	1
5	4	2
6	1	1
6	2	2
6	9	3
7	8	1
8	2	1
8	5	2
9	7	1
10	4	1
10	10	2
11	1	1
11	7	2
12	7	1
13	2	1
13	5	2
13	8	3
14	9	1
15	4	1
15	7	2
16	6	1
17	2	1
17	3	2
17	10	3
18	2	1
18	4	2
18	8	3
19	2	1
20	4	1
20	6	2
\.


--
-- TOC entry 5157 (class 0 OID 19457)
-- Dependencies: 236
-- Data for Name: books; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.books (id, title, slug, isbn, publication_year, page_count, description, stock, available_stock, created_at, updated_at, deleted_at) FROM stdin;
1	Quis et distinctio	quis-et-distinctio	9794337240222	1970	666	Aut omnis voluptatum et in. Sit repudiandae culpa nisi vero. Voluptas similique corrupti dolor.	6	4	2026-11-17 20:47:50	2026-11-17 20:47:50	\N
2	Dolorem enim commodi et at sit	dolorem-enim-commodi-et-at-sit	9791566391251	1971	168	Ut alias enim maiores vero. Id est officia ad ex. Ut aut ad soluta labore.	12	6	2026-11-17 20:47:50	2026-11-17 20:47:50	\N
3	Rerum non ut	rerum-non-ut	9784937853427	1995	434	Eaque qui aut enim sed. Voluptate reprehenderit ea quia perspiciatis sed ad ipsum. Quia reiciendis perspiciatis non ducimus.	31	24	2026-11-17 20:47:50	2026-11-17 20:47:50	\N
4	Qui laborum ut quia hic	qui-laborum-ut-quia-hic	9784958282527	1986	157	Ullam sed quos adipisci iste reiciendis. Dolorum veniam vero alias accusamus. Voluptas quia dolor aut nam. Est ea non enim fugiat voluptates sint.	28	4	2026-11-17 20:47:50	2026-11-17 20:47:50	\N
5	Iure possimus mollitia placeat dicta	iure-possimus-mollitia-placeat-dicta	9794749473058	2001	293	Non voluptas quibusdam ut ad voluptas. Rerum voluptatem quam laudantium error qui autem et. Laudantium ut cum eos.	21	5	2026-11-17 20:47:50	2026-11-17 20:47:50	\N
6	Quis at et et et rerum	quis-at-et-et-et-rerum	9783287863551	2016	267	Et et sit quos laboriosam ut esse fugit sed. Dicta et necessitatibus libero quia quia. Quisquam doloremque est quo delectus nesciunt. Aperiam ut quia necessitatibus non est. Aspernatur consequuntur possimus incidunt distinctio placeat.	26	16	2026-11-17 20:47:50	2026-11-17 20:47:50	\N
7	Nam unde expedita	nam-unde-expedita	9786525629148	2015	798	Ratione mollitia non mollitia expedita sequi. Aperiam ipsa ea quaerat molestiae minus. Voluptas facilis harum quo aliquam eaque sed animi. Sapiente impedit eligendi cumque fugit natus harum et.	49	45	2026-11-17 20:47:50	2026-11-17 20:47:50	\N
8	Dolorum voluptas minus	dolorum-voluptas-minus	9787725584589	2015	546	Aliquam vel consequuntur quis ut pariatur eum. Repudiandae soluta ut quia sequi ab sint. Quia porro dolorem illo est numquam reiciendis. Est ut qui consequatur laborum.	11	1	2026-11-17 20:47:50	2026-11-17 20:47:50	\N
9	Molestiae quis et ea perspiciatis	molestiae-quis-et-ea-perspiciatis	9797753988181	1983	483	Qui ut quasi labore libero. Velit sit veniam ipsa. Illo omnis nisi omnis aut.	10	5	2026-11-17 20:47:50	2026-11-17 20:47:50	\N
10	Ullam impedit provident velit quasi illum	ullam-impedit-provident-velit-quasi-illum	9789089589606	2006	593	Temporibus eveniet blanditiis molestias reiciendis. Quis et delectus quod numquam. Corporis aliquam sed dolores.	30	24	2026-11-17 20:47:50	2026-11-17 20:47:50	\N
11	At sed commodi ut	at-sed-commodi-ut	9791657044318	1990	270	Recusandae eum quaerat enim occaecati aut. Dolore ducimus quisquam voluptas aut minima aut. Quam dolorem commodi voluptates odio culpa harum veniam aut.	9	9	2026-11-17 20:47:50	2026-11-17 20:47:50	\N
12	Voluptas itaque neque perspiciatis enim	voluptas-itaque-neque-perspiciatis-enim	9795455270504	1985	872	Occaecati quaerat vitae aliquid vero fugiat repudiandae est quia. Facere ea eius ut placeat quo nisi quos.	43	24	2026-11-17 20:47:50	2026-11-17 20:47:50	\N
13	Magni autem corrupti id reiciendis	magni-autem-corrupti-id-reiciendis	9792797101947	2016	345	Quis dolore nihil rem voluptatum dolorum reprehenderit odit cumque. Consequuntur possimus sed nam libero inventore officia minima. Suscipit consequatur qui velit ad et molestiae ea.	27	20	2026-11-17 20:47:50	2026-11-17 20:47:50	\N
14	Vel quisquam non ipsam ut	vel-quisquam-non-ipsam-ut	9783198377017	1994	650	Temporibus dolorem est rerum deleniti. Est iure velit vel ex est. Reprehenderit tempore hic explicabo impedit dolores.	32	8	2026-11-17 20:47:50	2026-11-17 20:47:50	\N
15	Pariatur qui consequatur	pariatur-qui-consequatur	9790801916914	2016	553	Nulla cum odit ipsum cumque officiis aspernatur cupiditate. Voluptatem quas magnam iste deleniti. Et veniam autem modi enim in. Maxime aut maiores quis.	1	0	2026-11-17 20:47:50	2026-11-17 20:47:50	\N
16	Aut ut est earum ut	aut-ut-est-earum-ut	9793596132910	2010	988	Quia dolorem repellat nihil corporis veniam et aliquid. Omnis rerum accusamus et. Porro excepturi adipisci et. Aut nihil ea enim ut autem repudiandae aspernatur optio.	12	12	2026-11-17 20:47:50	2026-11-17 20:47:50	\N
17	Perspiciatis eaque alias qui cum	perspiciatis-eaque-alias-qui-cum	9791696199574	1976	990	Soluta vel velit occaecati dolor accusantium. Omnis ea eum dicta earum aspernatur hic quis quisquam. Ex rerum sint est qui repellendus.	11	8	2026-11-17 20:47:50	2026-11-17 20:47:50	\N
18	Nobis aperiam omnis ut velit	nobis-aperiam-omnis-ut-velit	9795182828436	1993	814	Ea neque ullam asperiores molestiae at velit. Asperiores asperiores sint dolorem aperiam cum voluptates. Voluptatem dolores deleniti rerum iusto. Aut pariatur dolore corporis consequuntur optio quam.	32	27	2026-11-17 20:47:50	2026-11-17 20:47:50	\N
19	Harum ut qui quo quia sequi	harum-ut-qui-quo-quia-sequi	9790689996909	2018	242	Laudantium laudantium cupiditate suscipit nihil officia quam. Et delectus distinctio quas adipisci qui vero quia earum. Rerum aliquam debitis odio accusamus repellendus eligendi. Odit rerum atque dolore qui.	9	0	2026-11-17 20:47:50	2026-11-17 20:47:50	\N
20	Quibusdam tempore ut voluptatem expedita	quibusdam-tempore-ut-voluptatem-expedita	9797709193959	1976	572	Dolorum quas earum veniam dolorem consequatur mollitia voluptatem est. Veniam ut distinctio consectetur et minima illo iure. Temporibus incidunt laborum nobis officiis voluptate at. Voluptatem et ex provident sunt maiores error ut.	30	18	2026-11-17 20:47:50	2026-11-17 20:47:50	\N
\.


--
-- TOC entry 5143 (class 0 OID 19327)
-- Dependencies: 222
-- Data for Name: cache; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cache (key, value, expiration) FROM stdin;
\.


--
-- TOC entry 5144 (class 0 OID 19338)
-- Dependencies: 223
-- Data for Name: cache_locks; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cache_locks (key, owner, expiration) FROM stdin;
\.


--
-- TOC entry 5149 (class 0 OID 19380)
-- Dependencies: 228
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
\.


--
-- TOC entry 5147 (class 0 OID 19365)
-- Dependencies: 226
-- Data for Name: job_batches; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.job_batches (id, name, total_jobs, pending_jobs, failed_jobs, failed_job_ids, options, cancelled_at, created_at, finished_at) FROM stdin;
\.


--
-- TOC entry 5146 (class 0 OID 19350)
-- Dependencies: 225
-- Data for Name: jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.jobs (id, queue, payload, attempts, reserved_at, available_at, created_at) FROM stdin;
\.


--
-- TOC entry 5160 (class 0 OID 19501)
-- Dependencies: 239
-- Data for Name: loans; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.loans (id, user_id, book_id, borrowed_at, estimated_return_date, return_date, status, created_at, updated_at) FROM stdin;
1	3	10	2026-11-09 05:15:11	2026-11-22	\N	lost	\N	\N
2	9	18	2026-10-25 22:46:29	2026-11-08	\N	overdue	\N	\N
3	13	19	2026-11-12 05:03:27	2026-11-21	2026-11-20	returned	\N	\N
4	5	10	2026-11-12 22:59:01	2026-11-27	\N	active	\N	\N
5	6	20	2026-10-16 19:57:09	2026-10-28	\N	overdue	\N	\N
6	5	10	2026-11-05 08:48:34	2026-11-20	\N	active	\N	\N
7	5	1	2026-11-11 05:02:10	2026-11-21	\N	lost	\N	\N
8	8	10	2026-11-06 21:41:59	2026-11-21	2026-11-10	returned	\N	\N
9	16	18	2026-11-06 02:58:47	2026-11-17	\N	lost	\N	\N
10	5	20	2026-11-05 20:55:08	2026-11-14	\N	active	\N	\N
\.


--
-- TOC entry 5142 (class 0 OID 19318)
-- Dependencies: 221
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	0001_01_01_000001_create_cache_table	1
2	0001_01_01_000002_create_jobs_table	1
3	2026_11_10_145529_create_users_table	1
4	2026_11_10_150437_create_authors_table	1
5	2026_11_10_151245_create_books_table	1
6	2026_11_10_151949_create_book_author_table	1
7	2026_11_10_152608_create_loans_table	1
8	2026_11_10_190020_create_personal_access_tokens_table	1
9	2026_11_17_102856_add_password_to_users_table	1
\.


--
-- TOC entry 5152 (class 0 OID 19422)
-- Dependencies: 231
-- Data for Name: password_reset_tokens; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.password_reset_tokens (email, token, created_at) FROM stdin;
\.


--
-- TOC entry 5162 (class 0 OID 19529)
-- Dependencies: 241
-- Data for Name: personal_access_tokens; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.personal_access_tokens (id, tokenable_type, tokenable_id, name, token, abilities, last_used_at, expires_at, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 5153 (class 0 OID 19431)
-- Dependencies: 232
-- Data for Name: sessions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.sessions (id, user_id, ip_address, user_agent, payload, last_activity) FROM stdin;
\.


--
-- TOC entry 5151 (class 0 OID 19400)
-- Dependencies: 230
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id, name, last_name, email, phone_number, created_at, updated_at, is_active, password) FROM stdin;
1	Administrador	Biblioteca	admin@medlibrary.test	+573000000000	2026-11-17 20:47:45	2026-11-17 20:47:45	t	$2y$12$au7zcuLji3OGG2yoBnsL9e9dFsEVr5a4MLmijymWKaahnMJ54hIbi
2	Onie Goodwin	Senger	josephine20@example.com	+593014973026	2026-11-17 20:47:50	2026-11-17 20:47:50	f	$2y$12$WGXHCfCl3n.FzNe/2XvnU.dOdlzuK/Va6QGsogw06JI.1.xaKu8pu
3	Dr. Prince Yundt	Brekke	bwest@example.org	+52438583828	2026-11-17 20:47:50	2026-11-17 20:47:50	t	$2y$12$ZU3e6Dj6.e3pxqE3w8xQPe8h3cp7RJ4cTH9cQpXvm.EwzRKoSOIGu
4	Mr. Zechariah Cummings	Corkery	barrows.amari@example.net	+2398172308	2026-11-17 20:47:50	2026-11-17 20:47:50	f	$2y$12$O43hUtFFnnyE8Wv6Yu0ubeKGUfGi3muVMMICfGuA4OdvMYO.12jhy
5	Jenifer Okuneva	Schmitt	cordelia.ruecker@example.com	+88913981949	2026-11-17 20:47:50	2026-11-17 20:47:50	t	$2y$12$p.6ms8daRxuwvKaVQUJiL.fEhOnK91066yFbSrGN5tmZ3vTvAKOB2
6	Dr. Emery Willms I	Hayes	glenna60@example.org	+169399961374	2026-11-17 20:47:50	2026-11-17 20:47:50	t	$2y$12$lwZvxZsiv6FRfLy95Ataaee2ezrAglSrjUg4Vvl3l8xQY/PKPjInu
7	Dr. Elroy DuBuque	Bailey	christop24@example.com	+37145495599	2026-11-17 20:47:50	2026-11-17 20:47:50	t	$2y$12$AZ3QPDvJdAAvQT1WDpdCR.hXzns/prpRqG8SAgJbhOHGmiOTdFGw2
8	Trever Wehner	Schaefer	tommie.langosh@example.net	+9275060303	2026-11-17 20:47:50	2026-11-17 20:47:50	f	$2y$12$ewix0ON5EgfGrpIOADXUS.iI2o.Q.Y7VGAsiHlv/lOsIU8U1jjAN2
9	Derick Tillman	Schimmel	lynch.rita@example.net	+755455256304	2026-11-17 20:47:50	2026-11-17 20:47:50	t	$2y$12$vBVGL8yw3scyvEZDqZYS9eCHi6DMwRAU5ufCqaZI6wMzQY7jI8ETG
10	Rowena Sauer	Kemmer	kara27@example.net	+531777862188	2026-11-17 20:47:50	2026-11-17 20:47:50	t	$2y$12$UFEf1unTELic6PU3HPBYQOH25/SaZaU7jWueg5mYCFOcYTpgq2nQy
11	Dr. Abraham Bogan DVM	Kling	dbins@example.org	+741569125362	2026-11-17 20:47:50	2026-11-17 20:47:50	f	$2y$12$n.uzLj7K0xXyGNgwzxTwGeQUQuvrH1bmITSQZldoeRcas5kj7CtkS
12	Clinton Bosco Sr.	Price	darren.raynor@example.com	+2875841928	2026-11-17 20:47:50	2026-11-17 20:47:50	t	$2y$12$DTkxBilcyHZkavl8WWrtf.oNH50NkjeM9QEyNNEn9dAyqEUXDZt6.
13	Dr. Ansley Carter Sr.	Romaguera	dcruickshank@example.com	+5028076010	2026-11-17 20:47:50	2026-11-17 20:47:50	f	$2y$12$F8PaLvTdtB/wyew2/Ff4PuhekxoHaK3RY5CRzblNRB.8.7IU9QFSW
14	Rusty Deckow	Hauck	hoppe.eladio@example.org	+3722054973	2026-11-17 20:47:50	2026-11-17 20:47:50	t	$2y$12$ub6Npwe6ziGLlvAbp8aMNuQmMOAkx3wLZsTrCvZEzgXy.RR7Xn38y
15	Ms. Kristin Rice	Witting	tressie.runolfsdottir@example.com	+1813846646	2026-11-17 20:47:50	2026-11-17 20:47:50	f	$2y$12$nLUWZ50XIO18BBIePGvsEeSaRqfebtUwBeex.n12PEGhUKA.G.ngm
16	Peggie Bogan	Toy	weissnat.nyasia@example.com	+8772470816	2026-11-17 20:47:50	2026-11-17 20:47:50	f	$2y$12$jrysq/w0kLYLF1eZnzwUiuQjKf8azutxEKxD2YN.OW4XVnL0ADRMS
\.


--
-- TOC entry 5178 (class 0 OID 0)
-- Dependencies: 233
-- Name: authors_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.authors_id_seq', 10, true);


--
-- TOC entry 5179 (class 0 OID 0)
-- Dependencies: 235
-- Name: books_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.books_id_seq', 20, true);


--
-- TOC entry 5180 (class 0 OID 0)
-- Dependencies: 227
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- TOC entry 5181 (class 0 OID 0)
-- Dependencies: 224
-- Name: jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.jobs_id_seq', 1, false);


--
-- TOC entry 5182 (class 0 OID 0)
-- Dependencies: 238
-- Name: loans_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.loans_id_seq', 10, true);


--
-- TOC entry 5183 (class 0 OID 0)
-- Dependencies: 220
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.migrations_id_seq', 9, true);


--
-- TOC entry 5184 (class 0 OID 0)
-- Dependencies: 240
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.personal_access_tokens_id_seq', 1, false);


--
-- TOC entry 5185 (class 0 OID 0)
-- Dependencies: 229
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_id_seq', 16, true);


--
-- TOC entry 4971 (class 2606 OID 19454)
-- Name: authors authors_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.authors
    ADD CONSTRAINT authors_pkey PRIMARY KEY (id);


--
-- TOC entry 4981 (class 2606 OID 19499)
-- Name: book_author book_author_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.book_author
    ADD CONSTRAINT book_author_pkey PRIMARY KEY (book_id, author_id);


--
-- TOC entry 4973 (class 2606 OID 19477)
-- Name: books books_isbn_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.books
    ADD CONSTRAINT books_isbn_unique UNIQUE (isbn);


--
-- TOC entry 4975 (class 2606 OID 19472)
-- Name: books books_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.books
    ADD CONSTRAINT books_pkey PRIMARY KEY (id);


--
-- TOC entry 4978 (class 2606 OID 19475)
-- Name: books books_slug_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.books
    ADD CONSTRAINT books_slug_unique UNIQUE (slug);


--
-- TOC entry 4944 (class 2606 OID 19347)
-- Name: cache_locks cache_locks_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cache_locks
    ADD CONSTRAINT cache_locks_pkey PRIMARY KEY (key);


--
-- TOC entry 4941 (class 2606 OID 19336)
-- Name: cache cache_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cache
    ADD CONSTRAINT cache_pkey PRIMARY KEY (key);


--
-- TOC entry 4952 (class 2606 OID 19395)
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- TOC entry 4954 (class 2606 OID 19398)
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- TOC entry 4949 (class 2606 OID 19378)
-- Name: job_batches job_batches_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.job_batches
    ADD CONSTRAINT job_batches_pkey PRIMARY KEY (id);


--
-- TOC entry 4946 (class 2606 OID 19363)
-- Name: jobs jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- TOC entry 4983 (class 2606 OID 19514)
-- Name: loans loans_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.loans
    ADD CONSTRAINT loans_pkey PRIMARY KEY (id);


--
-- TOC entry 4938 (class 2606 OID 19326)
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- TOC entry 4964 (class 2606 OID 19430)
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);


--
-- TOC entry 4986 (class 2606 OID 19541)
-- Name: personal_access_tokens personal_access_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_pkey PRIMARY KEY (id);


--
-- TOC entry 4988 (class 2606 OID 19544)
-- Name: personal_access_tokens personal_access_tokens_token_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_token_unique UNIQUE (token);


--
-- TOC entry 4967 (class 2606 OID 19440)
-- Name: sessions sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);


--
-- TOC entry 4956 (class 2606 OID 19418)
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- TOC entry 4960 (class 2606 OID 19420)
-- Name: users users_phone_number_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_phone_number_unique UNIQUE (phone_number);


--
-- TOC entry 4962 (class 2606 OID 19414)
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- TOC entry 4969 (class 1259 OID 19455)
-- Name: authors_full_search_trgm_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX authors_full_search_trgm_idx ON public.authors USING gin (((((((COALESCE(name, ''::character varying))::text || ' '::text) || (COALESCE(last_name, ''::character varying))::text) || ' '::text) || (COALESCE(nationality, ''::character varying))::text)) public.gin_trgm_ops);


--
-- TOC entry 4976 (class 1259 OID 19478)
-- Name: books_publication_year_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX books_publication_year_index ON public.books USING btree (publication_year);


--
-- TOC entry 4979 (class 1259 OID 19473)
-- Name: books_title_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX books_title_index ON public.books USING btree (title);


--
-- TOC entry 4939 (class 1259 OID 19337)
-- Name: cache_expiration_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX cache_expiration_index ON public.cache USING btree (expiration);


--
-- TOC entry 4942 (class 1259 OID 19348)
-- Name: cache_locks_expiration_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX cache_locks_expiration_index ON public.cache_locks USING btree (expiration);


--
-- TOC entry 4950 (class 1259 OID 19396)
-- Name: failed_jobs_connection_queue_failed_at_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX failed_jobs_connection_queue_failed_at_index ON public.failed_jobs USING btree (connection, queue, failed_at);


--
-- TOC entry 4947 (class 1259 OID 19364)
-- Name: jobs_queue_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);


--
-- TOC entry 4984 (class 1259 OID 19545)
-- Name: personal_access_tokens_expires_at_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX personal_access_tokens_expires_at_index ON public.personal_access_tokens USING btree (expires_at);


--
-- TOC entry 4989 (class 1259 OID 19542)
-- Name: personal_access_tokens_tokenable_type_tokenable_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX personal_access_tokens_tokenable_type_tokenable_id_index ON public.personal_access_tokens USING btree (tokenable_type, tokenable_id);


--
-- TOC entry 4965 (class 1259 OID 19442)
-- Name: sessions_last_activity_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);


--
-- TOC entry 4968 (class 1259 OID 19441)
-- Name: sessions_user_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);


--
-- TOC entry 4957 (class 1259 OID 19416)
-- Name: users_last_name_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX users_last_name_index ON public.users USING btree (last_name);


--
-- TOC entry 4958 (class 1259 OID 19415)
-- Name: users_name_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX users_name_index ON public.users USING btree (name);


--
-- TOC entry 4990 (class 2606 OID 19493)
-- Name: book_author book_author_author_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.book_author
    ADD CONSTRAINT book_author_author_id_foreign FOREIGN KEY (author_id) REFERENCES public.authors(id) ON DELETE RESTRICT;


--
-- TOC entry 4991 (class 2606 OID 19488)
-- Name: book_author book_author_book_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.book_author
    ADD CONSTRAINT book_author_book_id_foreign FOREIGN KEY (book_id) REFERENCES public.books(id) ON DELETE CASCADE;


--
-- TOC entry 4992 (class 2606 OID 19520)
-- Name: loans loans_book_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.loans
    ADD CONSTRAINT loans_book_id_foreign FOREIGN KEY (book_id) REFERENCES public.books(id) ON DELETE RESTRICT;


--
-- TOC entry 4993 (class 2606 OID 19515)
-- Name: loans loans_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.loans
    ADD CONSTRAINT loans_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE RESTRICT;


-- Completed on 2026-11-17 15:54:21

--
-- PostgreSQL database dump complete
--

\unrestrict 1elGJ98b3TuycHfP8Tfw0GMh3XScgnWjTd5juPMHBhRaYJxZUFufBmUe0z8IyBG

-- Completed on 2026-11-17 15:54:21

--
-- PostgreSQL database cluster dump complete
--

