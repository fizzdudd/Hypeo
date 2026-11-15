-- =====================================================================
-- Hypeo · Esquema + datos de demo para Supabase (PostgreSQL)
-- Generado desde las migraciones de Laravel (php artisan migrate --seed)
-- Pegar completo en Supabase → SQL Editor → Run.
--
-- Demo: valentina@luzdecerro.cl / password
-- La tabla "migrations" queda poblada: Laravel sabe que ya están aplicadas.
-- =====================================================================

BEGIN;

CREATE TABLE public.cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration integer NOT NULL
);

CREATE TABLE public.cache_locks (
    key character varying(255) NOT NULL,
    owner character varying(255) NOT NULL,
    expiration integer NOT NULL
);

CREATE TABLE public.campanas (
    id bigint NOT NULL,
    producto_id bigint NOT NULL,
    red character varying(20) NOT NULL,
    tono character varying(20) NOT NULL,
    eslogan character varying(200) NOT NULL,
    publicacion text NOT NULL,
    hashtags json NOT NULL,
    cta character varying(200) NOT NULL,
    idea_imagen character varying(300) NOT NULL,
    texto_ia json,
    editada boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    prompt_imagen character varying(500)
);

CREATE SEQUENCE public.campanas_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

ALTER SEQUENCE public.campanas_id_seq OWNED BY public.campanas.id;

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;

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

CREATE TABLE public.jobs (
    id bigint NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    attempts smallint NOT NULL,
    reserved_at integer,
    available_at integer NOT NULL,
    created_at integer NOT NULL
);

CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;

CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);

CREATE TABLE public.productos (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    nombre character varying(120) NOT NULL,
    descripcion character varying(300) NOT NULL,
    precio integer NOT NULL,
    edad character varying(10) NOT NULL,
    intereses json NOT NULL,
    ubicacion character varying(120) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);

CREATE SEQUENCE public.productos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

ALTER SEQUENCE public.productos_id_seq OWNED BY public.productos.id;

CREATE TABLE public.sessions (
    id character varying(255) NOT NULL,
    user_id bigint,
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);

CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(255) NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;

ALTER TABLE ONLY public.campanas ALTER COLUMN id SET DEFAULT nextval('public.campanas_id_seq'::regclass);

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);

ALTER TABLE ONLY public.productos ALTER COLUMN id SET DEFAULT nextval('public.productos_id_seq'::regclass);

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);

INSERT INTO public.campanas (id, producto_id, red, tono, eslogan, publicacion, hashtags, cta, idea_imagen, texto_ia, editada, created_at, updated_at, prompt_imagen) VALUES (1, 1, 'instagram', 'cercano', 'Enciende la calma después del día.', 'Llegas a casa, apagas el celular y prendes tu vela de lavanda y eucalipto. 40 horas de cera de soya hecha a mano en Santiago para esos minutos que son solo tuyos. ¿Cuál es tu ritual para desconectarte?', '["#velasdesoya","#hechoenchile","#autocuidado","#santiago","#ritualnocturno","#velasartesanales"]', 'Escríbenos por DM y reserva la tuya', 'Vela encendida en velador, luz cálida, libro abierto', '{"eslogan":"Enciende la calma despu\u00e9s del d\u00eda.","publicacion":"Llegas a casa, apagas el celular y prendes tu vela de lavanda y eucalipto. 40 horas de cera de soya hecha a mano en Santiago para esos minutos que son solo tuyos. \u00bfCu\u00e1l es tu ritual para desconectarte?","hashtags":["#velasdesoya","#hechoenchile","#autocuidado","#santiago","#ritualnocturno","#velasartesanales"],"cta":"Escr\u00edbenos por DM y reserva la tuya","idea_imagen":"Vela encendida en velador, luz c\u00e1lida, libro abierto"}', false, '2026-11-15 02:31:53', '2026-11-15 02:31:53', NULL);
INSERT INTO public.campanas (id, producto_id, red, tono, eslogan, publicacion, hashtags, cta, idea_imagen, texto_ia, editada, created_at, updated_at, prompt_imagen) VALUES (2, 1, 'tiktok', 'divertido', 'POV: tu pieza huele a spa.', 'POV: llegaste destruida del trabajo y tu pieza huele a spa 🧘‍♀️ Lavanda + eucalipto, 40 horas de pura calma.', '["#velas","#pov","#selfcare","#hechoenchile","#emprendimiento"]', 'Link en la bio', 'Video vertical: abrir la puerta, encender la vela y tirarse a la cama', '{"eslogan":"POV: tu pieza huele a spa.","publicacion":"POV: llegaste destruida del trabajo y tu pieza huele a spa \ud83e\uddd8\u200d\u2640\ufe0f Lavanda + eucalipto, 40 horas de pura calma.","hashtags":["#velas","#pov","#selfcare","#hechoenchile","#emprendimiento"],"cta":"Link en la bio","idea_imagen":"Video vertical: abrir la puerta, encender la vela y tirarse a la cama"}', false, '2026-11-13 02:31:53', '2026-11-13 02:31:53', NULL);
INSERT INTO public.campanas (id, producto_id, red, tono, eslogan, publicacion, hashtags, cta, idea_imagen, texto_ia, editada, created_at, updated_at, prompt_imagen) VALUES (3, 1, 'linkedin', 'profesional', 'Regalos corporativos con aroma local.', 'En Luz de Cerro fabricamos velas de soya a mano en Santiago. Si buscas un regalo corporativo sustentable para tu equipo o clientes, armamos packs personalizados con tu marca y despacho a todo Chile.', '["#regaloscorporativos","#pyme","#hechoenchile","#sustentable","#bienestarlaboral"]', 'Cotiza tu pack corporativo por mensaje', 'Cajas de regalo apiladas sobre escritorio de oficina, luz natural', '{"eslogan":"Regalos corporativos con aroma local.","publicacion":"En Luz de Cerro fabricamos velas de soya a mano en Santiago. Si buscas un regalo corporativo sustentable para tu equipo o clientes, armamos packs personalizados con tu marca y despacho a todo Chile.","hashtags":["#regaloscorporativos","#pyme","#hechoenchile","#sustentable","#bienestarlaboral"],"cta":"Cotiza tu pack corporativo por mensaje","idea_imagen":"Cajas de regalo apiladas sobre escritorio de oficina, luz natural"}', false, '2026-11-09 02:31:54', '2026-11-09 02:31:54', NULL);
INSERT INTO public.campanas (id, producto_id, red, tono, eslogan, publicacion, hashtags, cta, idea_imagen, texto_ia, editada, created_at, updated_at, prompt_imagen) VALUES (4, 2, 'facebook', 'elegante', 'El aroma del bosque, en tu living.', 'Cedro y madera en un vaso de vidrio reciclado: una vela que decora apagada y transforma el ambiente encendida. 50 horas de duración, hecha a mano en Santiago.', '["#decoracion","#hogar","#velasdesoya","#dise\u00f1olocal","#santiago"]', 'Pídela hoy con despacho en la RM', 'Vela sobre mesa de centro de madera, plantas y manta tejida', '{"eslogan":"El aroma del bosque, en tu living.","publicacion":"Cedro y madera en un vaso de vidrio reciclado: una vela que decora apagada y transforma el ambiente encendida. 50 horas de duraci\u00f3n, hecha a mano en Santiago.","hashtags":["#decoracion","#hogar","#velasdesoya","#dise\u00f1olocal","#santiago"],"cta":"P\u00eddela hoy con despacho en la RM","idea_imagen":"Vela sobre mesa de centro de madera, plantas y manta tejida"}', false, '2026-11-12 02:31:54', '2026-11-12 02:31:54', NULL);
INSERT INTO public.campanas (id, producto_id, red, tono, eslogan, publicacion, hashtags, cta, idea_imagen, texto_ia, editada, created_at, updated_at, prompt_imagen) VALUES (5, 3, 'instagram', 'divertido', 'Cuatro aromas, cero excusas para no regalar.', '¿Cumpleaños, amigo secreto o “porque sí”? 🎁 Nuestro set trae 4 mini velas para que elijan su aroma favorito.', '["#regalos","#amigosecreto","#velas","#hechoenchile","#emprendechile"]', 'Compra por DM', 'Caja abierta con las 4 mini velas y papel kraft, vista cenital', '{"eslogan":"Cuatro aromas, cero excusas para no regalar.","publicacion":"\u00bfCumplea\u00f1os, amigo secreto o \u201cporque s\u00ed\u201d? \ud83c\udf81 Nuestro set trae 4 mini velas para que elijan su aroma favorito.","hashtags":["#regalos","#amigosecreto","#velas","#hechoenchile","#emprendechile"],"cta":"Compra por DM","idea_imagen":"Caja abierta con las 4 mini velas y papel kraft, vista cenital"}', false, '2026-11-05 02:31:54', '2026-11-05 02:31:54', NULL);

INSERT INTO public.migrations (id, migration, batch) VALUES (1, '0001_01_01_000000_create_users_table', 1);
INSERT INTO public.migrations (id, migration, batch) VALUES (2, '0001_01_01_000001_create_cache_table', 1);
INSERT INTO public.migrations (id, migration, batch) VALUES (3, '0001_01_01_000002_create_jobs_table', 1);
INSERT INTO public.migrations (id, migration, batch) VALUES (4, '2026_11_09_000001_create_productos_table', 1);
INSERT INTO public.migrations (id, migration, batch) VALUES (5, '2026_11_09_000002_create_campanas_table', 1);
INSERT INTO public.migrations (id, migration, batch) VALUES (6, '2026_11_15_000001_add_prompt_imagen_to_campanas_table', 1);

INSERT INTO public.productos (id, user_id, nombre, descripcion, precio, edad, intereses, ubicacion, created_at, updated_at) VALUES (1, 1, 'Vela Lavanda & Eucalipto', 'Vela de cera de soya, 40 h de duración, mecha de algodón. Aroma relajante para el final del día.', 12990, '25–34', '["bienestar","yoga","decoraci\u00f3n"]', 'Santiago, Chile', '2026-11-15 02:31:53', '2026-11-15 02:31:53');
INSERT INTO public.productos (id, user_id, nombre, descripcion, precio, edad, intereses, ubicacion, created_at, updated_at) VALUES (2, 1, 'Vela Cedro Andino', 'Vela en vaso de vidrio reciclado con notas de cedro y madera. 50 h de duración, hecha a mano en Santiago.', 14990, '35–44', '["hogar","dise\u00f1o"]', 'Santiago, Chile', '2026-11-15 02:31:53', '2026-11-15 02:31:53');
INSERT INTO public.productos (id, user_id, nombre, descripcion, precio, edad, intereses, ubicacion, created_at, updated_at) VALUES (3, 1, 'Set Mini Velas Regalo', 'Caja con 4 mini velas de soya (lavanda, cedro, vainilla y cítricos). Ideal para regalar.', 19990, '18–24', '["regalos","autocuidado"]', 'Chile', '2026-11-15 02:31:53', '2026-11-15 02:31:53');

INSERT INTO public.users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at) VALUES (1, 'Valentina Rojas', 'valentina@luzdecerro.cl', NULL, '$2y$12$Zfw56/9bV5yQtuebW5vGru8OHOkJcMm9vLPTdnvkSFq6K54h3kLnC', NULL, '2026-11-15 02:31:53', '2026-11-15 02:31:53');

SELECT pg_catalog.setval('public.campanas_id_seq', 5, true);

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);

SELECT pg_catalog.setval('public.migrations_id_seq', 6, true);

SELECT pg_catalog.setval('public.productos_id_seq', 3, true);

SELECT pg_catalog.setval('public.users_id_seq', 1, true);

ALTER TABLE ONLY public.cache_locks
    ADD CONSTRAINT cache_locks_pkey PRIMARY KEY (key);

ALTER TABLE ONLY public.cache
    ADD CONSTRAINT cache_pkey PRIMARY KEY (key);

ALTER TABLE ONLY public.campanas
    ADD CONSTRAINT campanas_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);

ALTER TABLE ONLY public.job_batches
    ADD CONSTRAINT job_batches_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);

ALTER TABLE ONLY public.productos
    ADD CONSTRAINT productos_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);

CREATE INDEX cache_expiration_index ON public.cache USING btree (expiration);

CREATE INDEX cache_locks_expiration_index ON public.cache_locks USING btree (expiration);

CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);

CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);

CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);

ALTER TABLE ONLY public.campanas
    ADD CONSTRAINT campanas_producto_id_foreign FOREIGN KEY (producto_id) REFERENCES public.productos(id) ON DELETE CASCADE;

ALTER TABLE ONLY public.productos
    ADD CONSTRAINT productos_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;

-- ---------------------------------------------------------------------
-- Seguridad: Supabase expone el esquema "public" por su API REST.
-- Activamos RLS sin políticas → la API (anon/authenticated) no puede leer
-- nada. Laravel se conecta como "postgres", que no está sujeto a RLS.
-- ---------------------------------------------------------------------
ALTER TABLE public.users                 ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.password_reset_tokens ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.sessions              ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.cache                 ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.cache_locks           ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.jobs                  ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.job_batches           ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.failed_jobs           ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.migrations            ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.productos             ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.campanas              ENABLE ROW LEVEL SECURITY;

COMMIT;
