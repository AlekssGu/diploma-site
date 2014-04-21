CREATE OR REPLACE VIEW last_readings 
AS
/* 
 * @created 12.04.2014 Aleksandrs Gusevs
 * @form Skats tiek izmantots klienta formā
 * @purpose Attēlo klienta pēdējos iesniegtos rādījumus
 */
SELECT  uvc.obj_id                     AS object_id,
        obj.client_id                  AS client_id,
        usr.username                   AS client_number,
        uvc.id                         AS usr_srv_id,
        (SELECT rdn.id 
           FROM readings rdn 
          WHERE rdn.meter_id = mtr.id 
          ORDER BY rdn.id DESC
          LIMIT 1)                      AS rdn_id,
        mtr.*,
        (SELECT rdn.lead 
           FROM readings rdn 
          WHERE rdn.meter_id = mtr.id 
          ORDER BY rdn.id DESC
          LIMIT 1,1)                      AS last_lead,
        (SELECT rdn.lead 
           FROM readings rdn 
          WHERE rdn.meter_id = mtr.id 
          ORDER BY rdn.id DESC
          LIMIT 1)                      AS lead,
        (SELECT rdn.status 
           FROM readings rdn 
          WHERE rdn.meter_id = mtr.id 
          ORDER BY rdn.id DESC
          LIMIT 1)                      AS status,
        (SELECT rdn.date_taken 
           FROM readings rdn 
          WHERE rdn.meter_id = mtr.id 
          ORDER BY rdn.id DESC
          LIMIT 1)                      AS date_taken,
	 uvc.is_active                  AS is_active
  FROM meters mtr
  JOIN user_services uvc ON uvc.id = mtr.service_id
  JOIN objects obj ON obj.id = uvc.obj_id
  JOIN users usr ON usr.id = obj.client_id;