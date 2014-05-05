CREATE OR REPLACE VIEW client_objects 
AS
/* 
 * @created 12.04.2014 Aleksandrs Gusevs
 * @form Skats tiek izmantots darbinieka formā - abonentu pārvaldības sadaļā
 * @purpose Attēlo klientu objektu informāciju
 */
SELECT obj.client_id                                    AS client_id,
       obj.id                                           AS object_id,
       obj.name                                         AS object_name,
       obj.notes                                        AS object_notes,
       obj.is_deleted                                   AS is_deleted,
       CONCAT(adr.street, ' ', adr.house, 
                IF(adr.flat != 0, ' - ', ''), 
                IF(adr.flat != 0, adr.flat, ''), ', ', 
                adr.district, ', ',  
                adr.post_code, ', ',
                cty.city_name)                          AS object_addr
  FROM objects obj
  JOIN addresses adr ON adr.id = obj.address_id
  JOIN cities cty ON cty.id = adr.city_id;