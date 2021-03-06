create user amministratore with encrypted password 'V4Nb3Vy4QJHmgGL9bj7Npds9';
grant select,insert,update,delete on player_attribute,team,player,league,public.match,country,initial_formation to amministratore;
grant select,usage on sequence league_id_seq,team_id_seq,country_id_seq to amministratore;
grant select on country to amministratore;
grant trigger on classifica to amministratore;
grant execute on function func_player_formation_assoc(initial_formation) to amministratore;
grant execute on function func_insert_team(team) to amministratore;
grant execute on function func_delete_team(team.id%TYPE) to amministratore;
grant execute on function func_update_team(team) to amministratore;
grant execute on function func_insert_match(match) to amministratore;
grant execute on function func_insert_country(country.name%TYPE) to amministratore;
grant execute on function func_delete_country(country.id%TYPE) to amministratore;
grant execute on function func_update_country(country) to amministratore;
grant execute on function func_insert_player(player) to amministratore;
grant execute on function func_insert_player_attribute(player_attribute) to amministratore;
grant execute on function func_insert_league(league.name%TYPE) to amministratore;
grant execute on function func_delete_league(league.id%TYPE) to amministratore;
grant execute on function func_update_league(league) to amministratore;
grant execute on function func_refresh_classifica() to amministratore;

create user operatore with encrypted password 'P4pj92v5Gk7sDk8NaWaNTK2h';
grant select on team to operatore;
grant all privileges on match to operatore;
grant select,usage on match_id_seq to operatore;
grant trigger on classifica to operatore;
grant execute on function func_refresh_classifica() to operatore;
grant execute on function func_insert_match(match) to operatore;
grant execute on function func_delete_match(public.match.id%TYPE,public.match.operator_id%TYPE) to operatore;
grant execute on function func_update_match(match) to operatore;

create user partner with encrypted password 'sL3FBmAxjFYnjsgBBN4HV8UF';
grant select on users to partner;
grant execute on function func_insert_bet(bets) to partner;
grant execute on function func_delete_bet(m_id int,p_id int, b char, curr char(3)) to partner;
grant execute on function func_update_bet(m_id int,p_id int, b char, curr char(3), new_m_id int, new_b char, new_curr char(3), new_val numeric) to partner;
grant all privileges on table bets to partner;

create user login_user with encrypted password 'rH4KJz5Es2ex7QUqvVntMjSM';
grant select,insert on users,login_attempt to login_user;
grant select on team,bets,currency,player,country,match,player_attribute,
    league,bet_society,public.classifica to login_user;
grant select,usage on sequence users_id_seq to login_user;
grant execute on function get_best_players(match_id public.match.id%TYPE),func_get_best_players_from_team(match_date public.match.date%TYPE, t_id team.id%TYPE) to login_user;

grant usage on schema public to amministratore,operatore,partner,login_user;


/*
drop user amministratore;
drop user operatore;
drop user partner;
drop user login_user;*/