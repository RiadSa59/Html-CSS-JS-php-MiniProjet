--
-- PostgreSQL database dump
--

-- Dumped from database version 9.4.11
-- Dumped by pg_dump version 9.6.2

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;


--
-- Name: s10; Type: SCHEMA; Schema: -; Owner: -
--

CREATE SCHEMA s10;


SET search_path = s10, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: avatars; Type: TABLE; Schema: s10; Owner: -
--

CREATE TABLE avatars (
    login text NOT NULL,
    type text,
    contenu bytea
);


--
-- Name: interets; Type: TABLE; Schema: s10; Owner: -
--

CREATE TABLE interets (
    index integer NOT NULL,
    login text,
    sujet text
);


--
-- Name: interets_index_seq; Type: SEQUENCE; Schema: s10; Owner: -
--

CREATE SEQUENCE interets_index_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: interets_index_seq; Type: SEQUENCE OWNED BY; Schema: s10; Owner: -
--

ALTER SEQUENCE interets_index_seq OWNED BY interets.index;


--
-- Name: still_alive; Type: TABLE; Schema: s10; Owner: -
--

CREATE TABLE still_alive (
    login text NOT NULL,
    stamp timestamp with time zone DEFAULT now()
);


--
-- Name: users; Type: TABLE; Schema: s10; Owner: -
--

CREATE TABLE users (
    login text NOT NULL,
    nom text,
    prenom text,
    password text NOT NULL
);


--
-- Name: interets index; Type: DEFAULT; Schema: s10; Owner: -
--

ALTER TABLE ONLY interets ALTER COLUMN index SET DEFAULT nextval('interets_index_seq'::regclass);


--
-- Name: avatars avatars_pkey; Type: CONSTRAINT; Schema: s10; Owner: -
--

ALTER TABLE ONLY avatars
    ADD CONSTRAINT avatars_pkey PRIMARY KEY (login);


--
-- Name: interets interets_pkey; Type: CONSTRAINT; Schema: s10; Owner: -
--

ALTER TABLE ONLY interets
    ADD CONSTRAINT interets_pkey PRIMARY KEY (index);


--
-- Name: still_alive still_alive_pkey; Type: CONSTRAINT; Schema: s10; Owner: -
--

ALTER TABLE ONLY still_alive
    ADD CONSTRAINT still_alive_pkey PRIMARY KEY (login);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: s10; Owner: -
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_pkey PRIMARY KEY (login);


--
-- Name: avatars avatars_login_fkey; Type: FK CONSTRAINT; Schema: s10; Owner: -
--

ALTER TABLE ONLY avatars
    ADD CONSTRAINT avatars_login_fkey FOREIGN KEY (login) REFERENCES users(login);


--
-- Name: interets interets_login_fkey; Type: FK CONSTRAINT; Schema: s10; Owner: -
--

ALTER TABLE ONLY interets
    ADD CONSTRAINT interets_login_fkey FOREIGN KEY (login) REFERENCES users(login);


--
-- Name: still_alive still_alive_login_fkey; Type: FK CONSTRAINT; Schema: s10; Owner: -
--

ALTER TABLE ONLY still_alive
    ADD CONSTRAINT still_alive_login_fkey FOREIGN KEY (login) REFERENCES users(login);


--
-- PostgreSQL database dump complete
--

