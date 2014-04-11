CREATE OR REPLACE VIEW last_readings AS
SELECT  mtr.*,
        (select rdn.lead 
           from readings rdn 
          where rdn.meter_id = mtr.id 
          order by rdn.id desc 
          limit 1) as lead,
        (select rdn.date_taken 
           from readings rdn 
          where rdn.meter_id = mtr.id 
          order by rdn.id desc 
          limit 1) as date_taken,
         obj.client_id as client_id
  FROM meters mtr
  JOIN objects obj on obj.id = mtr.object_id