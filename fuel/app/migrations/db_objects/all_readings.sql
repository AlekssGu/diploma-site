CREATE OR REPLACE VIEW all_readings
AS
/* 
 * @created 12.04.2014 Aleksandrs Gusevs
 * @form Skats tiek izmantots grafika veidošanā klienta formā
 * @purpose Attēlo klienta visus iesniegtos rādījumus
 */
SELECT  rdn.*,
        obj.client_id                    AS client_id,
        (SELECT rdn1.lead 
           FROM readings rdn1 
          WHERE rdn1.meter_id = mtr.id 
			AND rdn1.lead <= rdn.lead
          ORDER BY rdn1.id DESC
          LIMIT 1,1)                      AS last_lead
FROM readings rdn
JOIN meters mtr ON (mtr.id = rdn.meter_id) 
JOIN user_services usv ON (usv.id = mtr.service_id) 
JOIN objects obj ON (obj.id = usv.obj_id) 
WHERE rdn.status = 'Apstiprināts'
  AND mtr.water_type = 'A'
  AND usv.is_active = 'Y'
  AND obj.is_deleted = 'N';