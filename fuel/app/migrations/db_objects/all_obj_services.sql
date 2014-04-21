CREATE OR REPLACE VIEW all_obj_services
AS
/* 
 * @created 12.04.2014 Aleksandrs Gusevs
 * @form Skats tiek izmantots darbinieka formā - abonentu pārvaldības sadaļā
 * @purpose Attēlo klientu objektu pakalpojumus
 */
SELECT     uvc.obj_id                                   AS object_id,
           uvc.srv_id                                   AS service_id,
           uvc.id                                       AS usr_srv_id,
	   CONCAT( srv.name,  ' - ', srv.description )  AS service_name, 
	   uvc.date_from                                AS srv_from, 
	   uvc.date_to                                  AS srv_to,
           uvc.is_active                                AS is_active
FROM user_services uvc
JOIN services srv ON srv.id = uvc.srv_id
JOIN codificators cdf ON cdf.id = srv.code_id;