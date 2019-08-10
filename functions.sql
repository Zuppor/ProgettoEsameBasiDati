--funzione per inserire match nel database. ritorna 0 se è andato a buon fine, 2 se vincoli di not null sono stati violati,
--1 se ci sono violazioni sulle chiave esterne, 3 se i vincoli unique sono violati, 4 se una chiave esterna non è presente
create or replace function func_insert_match(m match)
returns char as $result$

begin
    insert into match (id, home_team_id, away_team_id, season, stage, date, a_team_goal, h_team_goal, league_id, country_id, operator_id)
    values (m.id,m.home_team_id,m.away_team_id,m.season,m.stage,m.date,m.a_team_goal,m.h_team_goal,m.league_id,m.country_id,m.operator_id);

    if FOUND then
      return '0';
    else
      return '1';
    end if ;

    exception
    when not_null_violation then
      raise info 'Errore: vincolo di not null violato';
      return '2';
    when unique_violation then
      raise info 'Errore: stai inserendo dati relativi ad un match già presente';
      return '3';
    when foreign_key_violation then
      raise info 'Errore: chiave etserna non presente';
      return '4';
end;
$result$ language plpgsql;



create or replace function func_insert_country(country_name country.name%TYPE)
returns int as $result$
    declare
        ret_id country.id%TYPE;
    begin
        insert into country(name) values (country_name) returning id into ret_id;

        if FOUND then
            return ret_id;
        else
            return -1;
        end if;

    exception
        when not_null_violation then
            raise info 'Errore: vincolo di not null violato';
            return -2;
        when unique_violation then
            raise info 'Errore: stai inserendo dati relativi ad un paese già presente';
            return -3;
    end;
$result$ language plpgsql;


create or replace function func_insert_league(league_name league.name%TYPE)
    returns int as $result$
declare
    ret_id league.id%TYPE;
begin
    insert into league(name) values (league_name) returning id into ret_id;

    if FOUND then
        return ret_id;
    else
        return -1;
    end if;

exception
    when not_null_violation then
        raise info 'Errore: vincolo di not null violato';
        return -2;
    when unique_violation then
        raise info 'Errore: stai inserendo dati relativi ad una league già presente';
        return -3;
end;
$result$ language plpgsql;


create or replace function func_insert_team(t team)
returns char as $result$
    begin
        insert into team(id,short_name,long_name) values (t.id,t.short_name,t.long_name);

        if FOUND then
            return '0';
        else
            return '1';
        end if;

    exception
        when not_null_violation then
            raise info 'Errore: vincolo di not null violato';
            return '2';
        when unique_violation then
            raise info 'Errore: stai inserendo dati relativi ad un team già presente';
            return '3';
    end;
    $result$
language plpgsql;

/*
create or replace function modify_match(m_id int,c_id int,l_id int,se int,st int,d time,home_id int,away_id int,h_goals int,a_goals int,o_id int)
returns char as $$

  begin
    update match set country_id = c_id,league_id = l_id,season = se,stage = st,date = d,home_team_id = home_id,away_team_id = away_id,h_team_goal = h_goals,a_team_goal = a_goals
    where id = m_id and operator_id = o_id;

    if FOUND then
      return 0;
    else
      raise info 'Errore: non hai permessi su questa tupla';
      return '1';
    end if;

    exception
    when unique_violation then
      raise info 'Errore: stai inserendo dati relativi ad un match già presente';
      return '2';
    when not_null_violation then
      raise info 'Errore: vincolo di not null violato';
      return '3';
    when foreign_key_violation then
      raise info 'Errore: chiave etserna non presente';
      return '4';
  end;
  $$ language plpgsql;




create or replace function delete_match(m_id int,o_id int)
returns char as $$

  begin
    delete from match where operator_id = o_id and id = m_id;
  end;

  if FOUND then
    return '0';
  end if;

  return '1';

$$ language plpgsql;




create or replace function insert_bet(m_id int,p_id int,b char,v numeric,c_id char(3))
returns char as $$
    begin

      select match_id
      from bets
      join users u on bets.partner_id = u.id
      where match_id = m_id and bet = b
        and u.society_id = (select u.society_id from users where users.id = p_id);

    if not FOUND then
      insert into bets (match_id, partner_id, bet, value, currency_id) values
        (m_id,p_id,b,v,c_id);
      return '0';
    else
      raise info 'Errore: scommessa già presente';
      return '1';
    end if;

    exception
    when check_violation then
      raise info 'Errore: condizione check violata';
      return '2';
    when not_null_violation then
      raise info 'Errore: vincolo not null violato';
      return '3';
    when foreign_key_violation then
      raise info 'Errore: chiave etserna non presente';
      return '4';
  end;
$$ language plpgsql;



create or replace function modify_bet(m_id int,p_id int,b char,new_v numeric,new_cid char(3),new_mid int,new_b char)
returns char as $$
  begin

      update bets set match_id = new_mid, bet = new_b, value = new_v,currency_id = new_cid
        where partner_id = p_id and match_id = m_id and bet = b;

      if FOUND then
        return '0';
      else
        raise info 'Errore: scommessa non trovata';
        return '1';
      end if;

      exception
      when unique_violation then
        raise info 'Errore: scommessa già presente';
        return '2';
      when foreign_key_violation then
        raise info 'Errore: chiave etserna non presente';
        return '3';
      when not_null_violation then
        raise info 'Errore: vincolo not null violato';
        return '4';
  end;
$$ language plpgsql;


create or replace function delete_bet(m_id int,p_id int,b char)
returns char as $$

  begin
    delete from bets where match_id = m_id and partner_id = p_id and bet = b;

    if FOUND then
      return '0';
    end if;

    return '1';
  end;
$$ language plpgsql;


create type best_players as(
  match_id int,
  a_name varchar(100),
  a_birthday date,
  a_height int,
  a_weight int,
  a_rating int,
  h_name varchar(100),
  h_birthday date,
  h_height int,
  h_weight int,
  h_rating int);

create or replace function get_best_players()
returns setof best_players as $$
    declare
      tmp record;
      tmp2 best_players;
  begin
      for tmp in (select id,date,home_team_id,away_team_id from match order by date desc ) loop
        tmp2.match_id := tmp.id;

        select name,birthday,height,weight,overall_rating
        into tmp2.h_name,tmp2.h_birthday,tmp2.h_height,tmp2.h_weight,tmp2.h_rating
        from player
        join player_attribute pa on player.id = pa.player_id and pa.date <= tmp.date
        join participation p on player.id = p.player_id and p.team_id = tmp.home_team_id
        having overall_rating >= all(select overall_rating
                                  from player
                                   join player_attribute pa on player.id = pa.player_id and pa.date <= tmp.date
                                   join participation p on player.id = p.player_id and p.team_id = tmp.home_team_id);

        select name,birthday,height,weight,overall_rating
        into tmp2.a_name,tmp2.a_birthday,tmp2.a_height,tmp2.a_weight,tmp2.a_rating
        from player
         join player_attribute pa on player.id = pa.player_id and pa.date <= tmp.date
         join participation p on player.id = p.player_id and p.team_id = tmp.away_team_id
        having overall_rating >= all(select overall_rating
                                  from player
                                   join player_attribute pa on player.id = pa.player_id and pa.date <= tmp.date
                                   join participation p on player.id = p.player_id and p.team_id = tmp.away_team_id);

        return next tmp2;
      end loop;
  end;
$$ language plpgsql;

*/
create or replace function func_insert_player_attributes(attr player_attribute)
returns char as $result$
    begin
        insert into player_attribute (player_attribute) values (attr);

        if FOUND then
            return '0';
        else
            return '1';
        end if;

    exception
        when check_violation then
            raise info 'Errore: condizione check violata';
            return '2';
        when not_null_violation then
            raise info 'Errore: vincolo not null violato';
            return '3';
        when foreign_key_violation then
            raise info 'Errore: chiave etserna non presente';
            return '4';

    end;
$result$ language plpgsql;


create or replace function func_refresh_classifica()
    returns trigger
    security definer
as $$
begin
    refresh materialized view classifica;

    return new;
end;
$$ language plpgsql;