DROP DATABASE IF EXISTS CLASSCLOCK;
CREATE DATABASE IF NOT EXISTS CLASSCLOCK;

USE CLASSCLOCK;

CREATE TABLE IF NOT EXISTS Disciplina(
 id                 INT           NOT NULL  AUTO_INCREMENT,
 nome               VARCHAR(45)   NOT NULL,
 sigla              VARCHAR(5)    NOT NULL  UNIQUE,
 qtdProf            INT           NOT NULL,
 semestre			INT			  NOT NULL,
 qtdAulas			INT			  NOT NULL,
 status             BOOLEAN       NOT NULL  DEFAULT TRUE,
 PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS Grau (
  id    INT         NOT NULL AUTO_INCREMENT,
  nome  VARCHAR(45) NOT NULL,
  PRIMARY KEY (id)
);

INSERT INTO Grau (nome) VALUES ('Bacharel'),('Especialização (Pós-Graduação)'),('Licenciatura'),('Técnico'),('Tecnólogo');

CREATE TABLE IF NOT EXISTS Periodo (
	id		INT			NOT NULL AUTO_INCREMENT,
	nome	VARCHAR(20)	NOT NULL,
	PRIMARY KEY (id)
);

INSERT INTO Periodo (nome) VALUES ('Matutino'),('Vespertino'),('Noturno'),('Integral');

CREATE TABLE IF NOT EXISTS Curso (
  id            INT         NOT NULL AUTO_INCREMENT,
  nome          VARCHAR(45) NOT NULL  UNIQUE,
  sigla         VARCHAR(5)  NOT NULL  UNIQUE,
  qtdSemestres  INT         NOT NULL,
  grau          INT         NOT NULL,
  `status`      BOOLEAN     NOT NULL  DEFAULT TRUE,
  PRIMARY KEY (id),
  CONSTRAINT fk_curso_grau
    FOREIGN KEY (grau) REFERENCES Grau(id)
);

CREATE TABLE IF NOT EXISTS Curso_tem_disciplina (
   idDisciplina INT NOT NULL,
   idCurso      INT NOT NULL,
   CONSTRAINT fk_disciplina_curso
    FOREIGN KEY (idDisciplina) REFERENCES Disciplina(id),
   CONSTRAINT fk_curso_disciplina
    FOREIGN KEY (idCurso) REFERENCES Curso(id)
 );

CREATE TABLE IF NOT EXISTS Curso_tem_Periodo (
	 idCurso			INT NOT NULL,
	 idPeriodo		INT NOT NULL,
	 PRIMARY KEY (idCurso,idPeriodo),
	 CONSTRAINT fk_curso_periodo
	 	FOREIGN KEY (idCurso) REFERENCES Curso(id),
	 CONSTRAINT fk_periodo_curso
	 	FOREIGN KEY (idPeriodo) REFERENCES Periodo(id)
 );

CREATE TABLE IF NOT EXISTS Sala (
	id 					   INT            NOT NULL 			AUTO_INCREMENT,
	nSala 				 VARCHAR(5)     NOT NULL      UNIQUE,
	capMax			 	 INT            NOT NULL,
	tipo 				   VARCHAR(45)    NOT NULL,
	`status` 			 BOOLEAN        NOT NULL      DEFAULT TRUE,
	PRIMARY KEY(id)
);

CREATE TABLE IF NOT EXISTS Contrato(
	id					INT 			NOT NULL 			AUTO_INCREMENT,
    nome 				VARCHAR(45) 	NOT NULL,
    PRIMARY KEY(id)
);

INSERT INTO Contrato (nome) VALUES ('Exclusiva'),('Integral'),('Parcial');

CREATE TABLE IF NOT EXISTS Nivel(
	id					INT 			NOT NULL 			AUTO_INCREMENT,
    nome 				VARCHAR(45) 	NOT NULL,
    PRIMARY KEY(id)
);

INSERT INTO Nivel (nome) VALUES ('Graduação'),('Pós-Graduação'),('Mestrado'),('Doutorado');

CREATE TABLE IF NOT EXISTS Usuario (
	id 					  INT 			    NOT NULL 			AUTO_INCREMENT,
  nome 			    VARCHAR(32) 	NOT NULL,
  matricula 		VARCHAR(8)		NOT NULL			UNIQUE,
  email         varchar(255)  NOT NULL      UNIQUE,
  senha 		    CHAR(64) 	    NOT NULL,
  `status` 			BOOLEAN       NOT NULL  		DEFAULT TRUE,
  dae				BOOLEAN       NOT NULL  		DEFAULT FALSE,
  PRIMARY KEY(id)
);

INSERT INTO Usuario VALUES (NULL, 'admin','cg123456','admin@admin.com','46070d4bf934fb0d4b06d9e2c46e346944e322444900a435d7d9a95e6d7435f5', TRUE, FALSE);
INSERT INTO Usuario VALUES (NULL, 'dae','cg654321','dae@dae.com','46070d4bf934fb0d4b06d9e2c46e346944e322444900a435d7d9a95e6d7435f5', TRUE, TRUE);


CREATE TABLE IF NOT EXISTS Professor(
	id 					    INT 			    NOT NULL 			AUTO_INCREMENT,
	idCurso					    INT				    NULL,
  nascimento 			DATE 			    NOT NULL,
  coordenador 		BOOLEAN       NOT NULL  		DEFAULT FALSE,
  idContrato 			INT 			    NOT NULL,
  idNivel				  INT 			    NOT NULL,
  PRIMARY KEY(id),
  CONSTRAINT fk_professor_contrato
    FOREIGN KEY(idContrato) REFERENCES Contrato(id),
  CONSTRAINT fk_professor_nivel
	  FOREIGN KEY(idNivel) REFERENCES Nivel(id),
  CONSTRAINT fk_professor_usuario
    FOREIGN KEY(id) REFERENCES Usuario(id)
);

CREATE TABLE IF NOT EXISTS Competencia(
	idProfessor			INT 			NOT NULL,
    idDisciplina		INT 			NOT NULL,
    interesse 			BOOLEAN       	NOT NULL  	DEFAULT FALSE,
	active				BOOLEAN			NOT NULL	DEFAULT TRUE,
    PRIMARY KEY(idProfessor, idDisciplina),
    FOREIGN KEY(idProfessor) REFERENCES Professor(id),
    FOREIGN KEY(idDisciplina) REFERENCES Disciplina(id)
);

CREATE TABLE IF NOT EXISTS Turma(
	id 					INT 			NOT NULL AUTO_INCREMENT,
    sigla 				CHAR(10) 		NOT NULL UNIQUE,
    qtdAlunos 			INT 			NOT NULL,
    dp          		BOOLEAN     	NOT NULL  DEFAULT FALSE,
    disciplina			INT				NOT NULL,
	`status`      		BOOLEAN     	NOT NULL  DEFAULT TRUE,
	PRIMARY KEY (id),

    CONSTRAINT fk_turma_disciplina
    FOREIGN KEY (disciplina) REFERENCES Disciplina(id)
);

CREATE TABLE IF NOT EXISTS Disponibilidade(
	id					INT				NOT NULL  AUTO_INCREMENT,
	idPeriodo 			INT 			NOT NULL,
    idProfessor 		INT 			NOT NULL,
    dia 				VARCHAR(45) 	NOT NULL,
    inicio 				TIME 			NOT NULL,
    fim 				TIME 			NOT NULL,
    `status`      		BOOLEAN     	NOT NULL  DEFAULT TRUE,
    hasDisponibilidade	BOOLEAN			NOT NULL  DEFAULT TRUE,

    PRIMARY KEY(id),

    FOREIGN KEY(idPeriodo) REFERENCES Periodo(id),
    FOREIGN KEY(idProfessor) REFERENCES Professor(id)
);

CREATE TABLE IF NOT EXISTS CoordenadorDe(
	idCoordenador INT NOT NULL,
    idProfessor INT NOT NULL,

    PRIMARY KEY(idCoordenador, idProfessor),

    CONSTRAINT fk_coordenadorde_coordenador
    FOREIGN KEY (idCoordenador) REFERENCES Professor(id),

    CONSTRAINT fk_coordenadorde_professor
    FOREIGN KEY (idProfessor) REFERENCES Professor(id)

);

CREATE VIEW disciplinaSigla AS SELECT Competencia.idProfessor, id, concat(nome, ' (', sigla,')') as nome, sigla, qtdProf, semestre, qtdAulas, status, Competencia.active FROM `Competencia`
	JOIN `Disciplina` ON `Disciplina`.`id` = `Competencia`.`idDisciplina`;
