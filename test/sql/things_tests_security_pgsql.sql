--
-- PostgreSQL database dump
--

-- Dumped from database version 10.3
-- Dumped by pg_dump version 10.3

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: -
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


--
-- Name: element_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.element_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: co_security_nodes; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.co_security_nodes (
    id bigint DEFAULT nextval('public.element_id_seq'::regclass) NOT NULL,
    api_key character varying(255) DEFAULT NULL::character varying,
    login_id character varying(255) DEFAULT NULL::character varying,
    access_class character varying(255) NOT NULL,
    last_access timestamp without time zone NOT NULL,
    read_security_id bigint,
    write_security_id bigint,
    object_name character varying(255) DEFAULT NULL::character varying,
    access_class_context character varying(4095) DEFAULT NULL::character varying,
    ids character varying(4095) DEFAULT NULL::character varying
);


--
-- Data for Name: co_security_nodes; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.co_security_nodes (id, api_key, login_id, access_class, last_access, read_security_id, write_security_id, object_name, access_class_context, ids) FROM stdin;
1	\N	\N	CO_Security_Node	1970-01-01 00:00:00	-1	-1	\N	\N	\N
3	\N	\N	CO_Security_Node	1970-01-01 00:00:00	-1	-1	\N	\N	\N
4	\N	\N	CO_Security_Node	1970-01-01 00:00:00	-1	-1	\N	\N	\N
5	\N	\N	CO_Security_Node	1970-01-01 00:00:00	-1	-1	\N	\N	\N
6	\N	\N	CO_Security_Node	1970-01-01 00:00:00	-1	-1	\N	\N	\N
7	\N	MDAdmin	CO_Security_Login	1970-01-01 00:00:00	7	7	Maryland Login	a:2:{s:4:"lang";s:2:"en";s:15:\\"hashed_password\\";s:13:\\"CodYOzPtwxb4A\\";}	
8	\N	VAAdmin	CO_Security_Login	1970-01-01 00:00:00	8	8	Virginia Login	a:2:{s:4:"lang";s:2:"en";s:15:\\"hashed_password\\";s:13:\\"CodYOzPtwxb4A\\";}	
9	\N	DCAdmin	CO_Security_Login	1970-01-01 00:00:00	9	9	Washington DC Login	a:2:{s:4:"lang";s:2:"en";s:15:\\"hashed_password\\";s:13:\\"CodYOzPtwxb4A\\";}	
10	\N	WVAdmin	CO_Security_Login	1970-01-01 00:00:00	10	10	West Virginia Login	a:2:{s:4:"lang";s:2:"en";s:15:\\"hashed_password\\";s:13:\\"CodYOzPtwxb4A\\";}	
11	\N	DEAdmin	CO_Security_Login	1970-01-01 00:00:00	11	11	Delaware Login	a:2:{s:4:"lang";s:2:"en";s:15:\\"hashed_password\\";s:13:\\"CodYOzPtwxb4A\\";}	
12	\N	MainAdmin	CO_Security_Login	1970-01-01 00:00:00	12	12	Main Admin Login	a:2:{s:4:"lang";s:2:"en";s:15:\\"hashed_password\\";s:13:\\"CodYOzPtwxb4A\\";}	7,8,9,10,11
2	\N	admin	CO_Security_Login	2018-07-04 11:38:46	2	2	God Admin Login	a:2:{s:4:"lang";s:2:"en";s:15:"hashed_password";s:8:"379143f2";}	\N
\.


--
-- Name: element_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.element_id_seq', 12, true);


--
-- PostgreSQL database dump complete
--

