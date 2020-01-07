create trigger refresh_classifica after insert or update or delete
  on match
  for each statement
  execute procedure func_refresh_classifica();

create trigger refresh_classifica after insert or update or delete
  on league
  for each statement
execute procedure func_refresh_classifica();

create trigger refresh_classifica after insert or update or delete
  on country
  for each statement
execute procedure func_refresh_classifica();

create trigger refresh_classifica after insert or update or delete
  on team
  for each statement --todo: forse for each statement non è quello giusto. fare ricerca
execute procedure func_refresh_classifica();