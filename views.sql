
create materialized view classifica
as
  select * from func_generate_classifica() order by season desc,score desc;

refresh materialized view classifica;
drop materialized view classifica;
