create user amministratore with encrypted password 'V4Nb3Vy4QJHmgGL9bj7Npds9';
grant select,insert,update,delete on player_attribute,team,player,league,match,country,initial_formation to amministratore;
grant select,usage on sequence league_id_seq,team_id_seq,country_id_seq to amministratore;
grant select on country to amministratore;
grant trigger on classifica to amministratore;
grant execute on function func_player_formation_assoc(initial_formation) to amministratore;
grant execute on function func_insert_team(team) to amministratore;
grant execute on function func_insert_match(match) to amministratore;
grant execute on function func_insert_country(country.name%TYPE) to amministratore;
grant execute on function func_insert_player_attributes(attr player_attribute) to amministratore;
grant execute on function func_refresh_classifica() to amministratore;

create user operatore with encrypted password 'P4pj92v5Gk7sDk8NaWaNTK2h';
grant insert,update,delete on match to operatore;
grant trigger on classifica to operatore;
grant execute on function func_refresh_classifica() to operatore;

create user partner with encrypted password 'sL3FBmAxjFYnjsgBBN4HV8UF';
grant insert,update,delete on bets to partner;

create user login_user with encrypted password 'rH4KJz5Es2ex7QUqvVntMjSM';
grant select,insert on users,login_attempt to login_user;
grant select on bet_society,public.classifica to login_user;
grant select,usage on sequence users_id_seq to login_user;

grant usage on schema public to amministratore,operatore,partner,login_user;

drop user amministratore;
drop user operatore;
drop user partner;
drop user login_user;