-- INDENTING (emacs/vi): -*- mode:sql; tab-width:2; c-basic-offset:2; intent-tabs-mode:nil; -*- ex: set tabstop=2 expandtab:

/** PHP Application Programming Environment (PHP-APE)
 *
 * <P><B>COPYRIGHT:</B></P>
 * <PRE>
 * PHP Application Programming Environment (PHP-APE)
 * Copyright (C) 2005-2006 Cedric Dufour
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 * </PRE>
 *
 * @package PHP_APE
 * @subpackage Database
 */


/*
 * TABLE: Identity
 ********************************************************************************/

-- Table (and Primary Key)
CREATE SEQUENCE sq_APE_Identity MINVALUE 10000 MAXVALUE 2147483647 INCREMENT 1 CACHE 1;
CREATE TABLE tb_APE_Identity ( pk bigint NOT NULL PRIMARY KEY DEFAULT( nextval( 'sq_APE_Identity' ) ) ) WITHOUT OIDS;

-- Status
ALTER TABLE tb_APE_Identity ADD COLUMN ts_InsertTime timestamp NOT NULL DEFAULT( CURRENT_TIMESTAMP );
ALTER TABLE tb_APE_Identity ADD COLUMN vc_InsertBy varchar(50) NOT NULL DEFAULT( 'SYSTEM' );
ALTER TABLE tb_APE_Identity ADD COLUMN b_UpdateAble boolean NOT NULL DEFAULT( true );
ALTER TABLE tb_APE_Identity ADD COLUMN ts_UpdateTime timestamp NOT NULL DEFAULT( '1970-01-01 00:00:00'::timestamp );
ALTER TABLE tb_APE_Identity ADD COLUMN vc_UpdateBy varchar(50) NOT NULL DEFAULT( 'SYSTEM' );
ALTER TABLE tb_APE_Identity ADD COLUMN b_DisableAble boolean NOT NULL DEFAULT( true );
ALTER TABLE tb_APE_Identity ADD COLUMN b_DisableFlag boolean NOT NULL DEFAULT( false );
ALTER TABLE tb_APE_Identity ADD COLUMN ts_DisableTime timestamp NOT NULL DEFAULT( '1970-01-01 00:00:00'::timestamp );
ALTER TABLE tb_APE_Identity ADD COLUMN vc_DisableBy varchar(50) NOT NULL DEFAULT( 'SYSTEM' );
ALTER TABLE tb_APE_Identity ADD COLUMN b_DeleteAble boolean NOT NULL DEFAULT( true );
-- ... indexes
CREATE INDEX ix_APE_Identity_Inserted ON tb_APE_Identity ( ts_Insert );
CREATE INDEX ix_APE_Identity_Updated ON tb_APE_Identity ( ts_Update );
CREATE INDEX ix_APE_Identity_Disabled ON tb_APE_Identity ( ts_Disable );

-- Client
ALTER TABLE tb_APE_Identity ADD COLUMN vc_Name varchar(50) NOT NULL UNIQUE CHECK( length( vc_Name ) > 0 );
ALTER TABLE tb_APE_Identity ADD COLUMN tx_Note text NULL;


/*
 * TABLE: Principal
 ********************************************************************************/

-- Table (and Primary Key)
CREATE TABLE tb_APE_Principal ( pk bigint NOT NULL UNIQUE REFERENCES tb_APE_Identity( pk ) ON DELETE CASCADE  ) WITHOUT OIDS;

-- Principal
ALTER TABLE tb_APE_Principal ADD COLUMN vc_Password varchar(50) NULL CHECK( vc_Password IS NULL || length( vc_Password ) >= 6 );
ALTER TABLE tb_APE_Principal ADD COLUMN d_Expiration date NULL;


/*
 * TABLE: Group
 ********************************************************************************/

-- Table (and Primary Key)
CREATE TABLE tb_APE_Group ( pk bigint NOT NULL UNIQUE REFERENCES tb_APE_Identity( pk ) ON DELETE CASCADE  ) WITHOUT OIDS;

-- Group
ALTER TABLE tb_APE_Group ADD COLUMN d_Expiration date NULL;


/*
 * TABLE: Groupship
 ********************************************************************************/

-- Table (and Primary Key)
CREATE SEQUENCE sq_APE_Groupship MINVALUE 10000 MAXVALUE 2147483647 INCREMENT 1 CACHE 1;
CREATE TABLE tb_APE_Groupship ( pk bigint NOT NULL PRIMARY KEY DEFAULT( nextval( 'sq_APE_Groupship' ) ) ) WITHOUT OIDS;

-- Status
ALTER TABLE tb_APE_Groupship ADD COLUMN ts_Insert timestamp NOT NULL DEFAULT( CURRENT_TIMESTAMP );
ALTER TABLE tb_APE_Groupship ADD COLUMN ts_Update timestamp NOT NULL DEFAULT( '1970-01-01 00:00:00'::timestamp );
ALTER TABLE tb_APE_Groupship ADD COLUMN ts_Disable timestamp NOT NULL DEFAULT( '1970-01-01 00:00:00'::timestamp );
ALTER TABLE tb_APE_Groupship ADD COLUMN b_UpdateAble boolean NOT NULL DEFAULT( true );
ALTER TABLE tb_APE_Groupship ADD COLUMN b_DisableAble boolean NOT NULL DEFAULT( true );
ALTER TABLE tb_APE_Groupship ADD COLUMN b_DeleteAble boolean NOT NULL DEFAULT( true );
ALTER TABLE tb_APE_Groupship ADD COLUMN b_DisableFlag boolean NOT NULL DEFAULT( false );
-- ... indexes
CREATE INDEX ix_APE_Groupship_Inserted ON tb_APE_Groupship ( ts_Insert );
CREATE INDEX ix_APE_Groupship_Updated ON tb_APE_Groupship ( ts_Update );
CREATE INDEX ix_APE_Groupship_Disabled ON tb_APE_Groupship ( ts_Disable );

-- Groupship
ALTER TABLE tb_APE_Groupship ADD COLUMN fk_Principal bigint NOT NULL REFERENCES tb_APE_Principal( pk ) ON DELETE CASCADE;
ALTER TABLE tb_APE_Groupship ADD COLUMN fk_Group bigint NOT NULL REFERENCES tb_APE_Group( pk ) ON DELETE CASCADE;
ALTER TABLE tb_APE_Groupship ADD COLUMN d_Expiration date NULL;


/*
 * TABLE: Client
 ********************************************************************************/

-- Table (and Primary Key)
CREATE SEQUENCE sq_APE_SessionClient MINVALUE 10000 MAXVALUE 2147483647 INCREMENT 1 CACHE 1;
CREATE TABLE tb_APE_SessionClient ( pk bigint NOT NULL PRIMARY KEY DEFAULT( nextval( 'sq_APE_SessionClient' ) ) ) WITHOUT OIDS;

-- Status
ALTER TABLE tb_APE_SessionClient ADD COLUMN ts_Insert timestamp NOT NULL DEFAULT( CURRENT_TIMESTAMP );
ALTER TABLE tb_APE_SessionClient ADD COLUMN ts_Update timestamp NOT NULL DEFAULT( '1970-01-01 00:00:00'::timestamp );
ALTER TABLE tb_APE_SessionClient ADD COLUMN ts_Disable timestamp NOT NULL DEFAULT( '1970-01-01 00:00:00'::timestamp );
ALTER TABLE tb_APE_SessionClient ADD COLUMN b_UpdateAble boolean NOT NULL DEFAULT( true );
ALTER TABLE tb_APE_SessionClient ADD COLUMN b_DisableAble boolean NOT NULL DEFAULT( true );
ALTER TABLE tb_APE_SessionClient ADD COLUMN b_DeleteAble boolean NOT NULL DEFAULT( true );
ALTER TABLE tb_APE_SessionClient ADD COLUMN b_DisableFlag boolean NOT NULL DEFAULT( false );
-- ... indexes
CREATE INDEX ix_APE_SessionClient_Inserted ON tb_APE_SessionClient ( ts_Insert );
CREATE INDEX ix_APE_SessionClient_Updated ON tb_APE_SessionClient ( ts_Update );
CREATE INDEX ix_APE_SessionClient_Disabled ON tb_APE_SessionClient ( ts_Disable );

-- Client
ALTER TABLE tb_APE_SessionClient ADD COLUMN vc_Client varchar( 100 ) NOT NULL UNIQUE CHECK( length( vc_Client ) > 0 );
ALTER TABLE tb_APE_SessionClient ADD COLUMN vc_Password varchar( 100 ) NOT NULL CHECK( length( vc_Password ) >= 6 );
ALTER TABLE tb_APE_SessionClient ADD COLUMN d_Expiration date NULL;

-- Parameters
ALTER TABLE tb_APE_SessionClient ADD COLUMN b_AllowLogin boolean NOT NULL DEFAULT( true );
ALTER TABLE tb_APE_SessionClient ADD COLUMN b_TrustLogin boolean NOT NULL DEFAULT( false ); -- ignore principal password for trusted login; useful for externally-handled authentication
ALTER TABLE tb_APE_SessionClient ADD COLUMN b_CreateLogin boolean NOT NULL DEFAULT( false ); -- create principal for trusted login; useful for externally-handled authentication
ALTER TABLE tb_APE_SessionClient ADD COLUMN i_TTL bigint NOT NULL DEFAULT( 3600 ) CHECK( i_TimeToLive > 0 );
ALTER TABLE tb_APE_SessionClient ADD COLUMN b_History boolean NOT NULL DEFAULT( false );


/*
 * TABLE: Session
 ********************************************************************************/

-- Table (and Primary Key)
CREATE SEQUENCE sq_APE_Session MINVALUE 10000 MAXVALUE 2147483647 INCREMENT 1 CACHE 1;
CREATE TABLE tb_APE_Session ( pk bigint NOT NULL PRIMARY KEY DEFAULT( nextval( 'sq_APE_Session' ) ) ) WITHOUT OIDS;

-- Status
ALTER TABLE tb_APE_Session ADD COLUMN ts_Insert timestamp NOT NULL DEFAULT( CURRENT_TIMESTAMP );
ALTER TABLE tb_APE_Session ADD COLUMN ts_Update timestamp NOT NULL DEFAULT( '1970-01-01 00:00:00'::timestamp );
ALTER TABLE tb_APE_Session ADD COLUMN ts_Disable timestamp NOT NULL DEFAULT( '1970-01-01 00:00:00'::timestamp );
ALTER TABLE tb_APE_Session ADD COLUMN b_UpdateAble boolean NOT NULL DEFAULT( true );
ALTER TABLE tb_APE_Session ADD COLUMN b_DisableAble boolean NOT NULL DEFAULT( true );
ALTER TABLE tb_APE_Session ADD COLUMN b_DeleteAble boolean NOT NULL DEFAULT( true );
ALTER TABLE tb_APE_Session ADD COLUMN b_DisableFlag boolean NOT NULL DEFAULT( false );
-- ... indexes
CREATE INDEX ix_APE_Session_Inserted ON tb_APE_Session ( ts_Insert );
CREATE INDEX ix_APE_Session_Updated ON tb_APE_Session ( ts_Update );
CREATE INDEX ix_APE_Session_Disabled ON tb_APE_Session ( ts_Disable );

-- Session
ALTER TABLE tb_APE_Session ADD COLUMN vc_Key varchar( 32 ) NOT NULL DEFAULT( md5( random ) );
ALTER TABLE tb_APE_Session ADD COLUMN ts_LastUsed timestamp NOT NULL DEFAULT( CURRENT_TIMESTAMP );
ALTER TABLE tb_APE_Session ADD COLUMN fk_SessionClient bigint NOT NULL REFERENCES tb_APE_SessionClient( pk ) ON DELETE RESTRICT;
ALTER TABLE tb_APE_Session ADD COLUMN fk_Principal bigint NULL REFERENCES tb_APE_Principal( pk ) ON DELETE SET NULL;


/*
 * END
 ********************************************************************************/
