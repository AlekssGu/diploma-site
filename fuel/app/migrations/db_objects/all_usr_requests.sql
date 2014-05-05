CREATE OR REPLACE VIEW all_usr_requests 
AS
/* 
 * @created 04.05.2014 Aleksandrs Gusevs
 * @form Skats tiek izmantots darbinieka formā - iesniegto datu sadaļā
 * @purpose Attēlo iesniegtos pakalpojumu pieprasījumu
 */
SELECT  sre.id                                                  AS request_id
       ,cln.username                                            AS client_number
       ,CONCAT(per.name, ' ', per.surname)                      AS fullname
       ,sre.date_from                                           AS date_from
       ,sre.date_to                                             AS date_to
       ,sre.notes                                               AS request_notes
       ,CONCAT(adr.street, ' ', adr.house, 
                IF(adr.flat != 0, ' - ', ''), 
                IF(adr.flat != 0, adr.flat, ''), ', ', 
                adr.district, ', ',  
                adr.post_code, ', ',
                cty.city_name)                                  AS object_address
       ,IF(sre.service_id IS NOT NULL, srv.name, '')            AS service_requested
       ,IF(sre.usr_srv_id IS NOT NULL, (SELECT srv2.name 
                                          FROM services srv2 
                                         WHERE uvc.srv_id = srv2.id),'')
                                                                AS service_dismissed
       ,FROM_UNIXTIME(sre.created_at)                           AS date_requested
       ,sre.status                                              AS status
  FROM usr_service_requests sre
  JOIN users cln ON cln.id = sre.client_id AND cln.is_active = 'Y'
  JOIN persons per ON per.id = cln.person_id
  JOIN objects obj ON obj.id = sre.object_id
  JOIN addresses adr ON adr.id = obj.address_id
  JOIN cities cty ON cty.id = adr.city_id
  LEFT OUTER JOIN services srv ON srv.id = sre.service_id
  LEFT OUTER JOIN user_services uvc ON uvc.id = sre.usr_srv_id;
