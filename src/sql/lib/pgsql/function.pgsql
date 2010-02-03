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
 * FUNCTIONS: Session
 ********************************************************************************/

/*
 * PRIVATE
 */

CREATE OR REPLACE FUNCTION fn_APE_PRIVATE_Session_check(bigint,varchar) RETURNS bigint AS '
DECLARE
-- ARGUMENTS
  A_pk ALIAS FOR $1;
  A_vc_Key ALIAS FOR $2;
-- VARIABLES
  V_pk bigint;
BEGIN
  -- Initialization
  V_pk := NULL;

  -- Retrieve session
  SELECT INTO V_pk
    _Session.pk
  FROM
    tb_APE_Session AS _Session
  INNER JOIN
    tb_APE_SessionClient AS _Client
    ON _Client.pk = _Session.fk_SessionClient
  WHERE
    NOT _Session.b_Disabled
    AND _Session.pk = A_pk
    AND _Session.vc_Key = A_vc_Key
    AND date_part( \'epoch\', CURRENT_TIMESTAMP - _Session.ts_LastUsed ) < _Client.i_TTL
  ;

  -- Update session
  IF FOUND THEN
    UPDATE tb_APE_Session SET ts_LastUsed = CURRENT_TIMESTAMP WHERE pk = V_pk;
  END IF;

  -- END
  RETURN V_pk;
END
' LANGUAGE 'plpgsql' STRICT STABLE SECURITY DEFINER;
REVOKE ALL ON FUNCTION fn_APE_PRIVATE_Session_check(bigint,varchar) FROM PUBLIC;

CREATE OR REPLACE FUNCTION fn_APE_PRIVATE_SessionIdentityPrincipal_getPK(bigint) RETURNS bigint AS '
SELECT
  _Principal.pk
FROM
  tb_APE_IdentityPrincipal AS _Principal
WHERE
  NOT _Principal.b_Disabled
  AND _Principal.fk = (SELECT fk_IdentityPrincipal FROM tb_APE_Session WHERE pk=($1))
  AND COALESCE( _Principal.d_Expiration > CURRENT_DATE, true )
' LANGUAGE 'plpgsql' STRICT STABLE SECURITY DEFINER;
REVOKE ALL ON FUNCTION fn_APE_PRIVATE_SessionIdentityPrincipal_getPK(bigint) FROM PUBLIC;

CREATE OR REPLACE FUNCTION fn_APE_PRIVATE_SessionIdentityGroup_getPK(bigint) RETURNS setof bigint AS '
SELECT
  _Group AS pk
FROM
  tb_APE_IdentityGroupship AS _Groupship
INNER JOIN
  tb_APE_IdentityGroup AS _Group
  ON _Group.pk = _Groupship.fk_IdentityGroup
WHERE
  NOT _Groupship.b_Disabled
  AND _Groupship.fk_Identity = (SELECT fk_IdentityPrincipal FROM tb_APE_Session WHERE pk=($1))
  AND COALESCE( _Groupship.d_Expiration > CURRENT_DATE, true )
  AND NOT _Group.b_Disabled
  AND COALESCE( _Group.d_Expiration > CURRENT_DATE, true )
' LANGUAGE 'plpgsql' STRICT STABLE SECURITY DEFINER;
REVOKE ALL ON FUNCTION fn_APE_PRIVATE_SessionIdentityGroup_getPK(bigint) FROM PUBLIC;

CREATE OR REPLACE FUNCTION fn_APE_PRIVATE_SessionIdentityObject_getPK(bigint) RETURNS setof bigint AS '
SELECT
  fn_APE_PRIVATE_SessionIdentityPrincipal_getPK($1)
UNION SELECT
  fn_APE_PRIVATE_SessionIdentityGroup_getPK($1)
' LANGUAGE 'plpgsql' STRICT STABLE SECURITY DEFINER;
REVOKE ALL ON FUNCTION fn_APE_PRIVATE_SessionIdentityObject_getPK(bigint) FROM PUBLIC;

/*
 * PUBLIC
 */

CREATE OR REPLACE VIEW vw_APE_PRIVATE_Session_key AS SELECT pk, vc_Key FROM tb_APE_Session;
CREATE OR REPLACE FUNCTION fn_APE_PUBLIC_Session_open(varchar,varchar,varchar,varchar) RETURNS setof vw_APE_PRIVATE_Session_key AS '
DECLARE
-- ARGUMENTS
  A_vc_Client ALIAS FOR $1;
  A_vc_ClientPasswordMD5 ALIAS FOR $2;
  A_vc_Principal ALIAS FOR $3;
  A_vc_PrincipalPasswordMD5 ALIAS FOR $4;
-- VARIABLES
  V_Client_ROW tb_APE_SessionClient%ROWTYPE;
  V_Principal_ROW tb_APE_IdentityPrincipal%ROWTYPE;
  V_fk_IdentityPrincipal bigint;
BEGIN
  -- Initialization
  V_fk_IdentityPrincipal := NULL;

  -- Retrieve client
  SELECT INTO V_Client_ROW
    _Client.*
  FROM
    tb_APE_SessionClient AS _Client
  WHERE
    NOT _Client.b_Disabled
    AND _Client.vc_Client = A_vc_Client
    AND md5( _Client.vc_Password ) = A_vc_ClientPasswordMD5
    AND COALESCE( _Client.d_Expiration > CURRENT_DATE, true )
  ;

  -- Check client
  IF NOT FOUND THEN
    RAISE NOTICE \'[fn_APE_PUBLIC_Session_open] Invalid client\';
    RETURN;
  END IF;

  -- Check login
  IF V_Client_ROW.b_AllowLogin THEN
    
    -- Retrieve principal
    SELECT INTO V_Principal_ROW
      _Principal.*
    FROM
      tb_APE_IdentityPrincipal AS _Principal
    WHERE
      _Principal.vc_Principal = A_vc_Principal
    ;

    -- Check principal
    IF NOT FOUND THEN

      IF NOT V_Client_ROW.b_CreateLogin THEN
        RAISE NOTICE \'[fn_APE_PUBLIC_Session_open] Invalid login\';
        RETURN;
      ELSE
        INSERT INTO tb_APE_IdentityObject( vc_Name ) VALUES( A_vc_Principal );
        V_fk_IdentityPrincipal := currval( \'sq_APE_IdentityObject\' );
        INSERT INTO tb_APE_IdentityPrincipal( pk ) VALUES( V_fk_IdentityPrincipal );
      END IF;

    ELSE

      IF V_Principal_ROW.b_Disabled OR
        ( NOT V_Client_ROW.b_TrustLogin AND md5( V_Principal_ROW.vc_Password ) = A_vc_PrincipalPasswordMD5 ) OR
        COALESCE( _Client.d_Expiration <= CURRENT_DATE, false )
      THEN
        RAISE NOTICE \'[fn_APE_PUBLIC_Session_open] Invalid login\';
        RETURN;
      END IF;
      V_fk_IdentityPrincipal := V_Principal_ROW.pk;

    END IF;
    
  END IF;

  -- Create session
  INSERT INTO tb_APE_Session( fk_Session_Client, fk_IndentityPrincipal )
  VALUES( V_Client_ROW.pk, V_fk_IdentityPrincipal );

  -- END
  SELECT * FROM vw_APE_PRIVATE_Session_key WHERE pk = currval( \'sq_APE_Session\' );
END
' LANGUAGE 'plpgsql' STABLE SECURITY DEFINER;



/*
 * END
 ********************************************************************************/
