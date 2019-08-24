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
            select id into ret_id from country where name like country_name limit 1;
            return ret_id;
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
        select id into ret_id from league where name like league_name limit 1;
        return ret_id;
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


create or replace function func_check_bet(b bets)
returns int as $result$
begin
    perform match_id,bet,currency_id,partner_id
    from bets
    join users s on bets.partner_id = s.id
    where s.bet_society_id = (select bet_society_id from users where id = b.partner_id)
      and match_id = b.match_id and bet = b.bet and currency_id = b.currency_id;

    if FOUND then
        return 1;
    else
        return 0;
    end if;
end;
$result$ language plpgsql;


create or replace function func_insert_bet(b bets)
returns char as $result$
    begin

        if (select func_check_bet(b) = 0)  then
            insert into bets (match_id, partner_id, bet, currency_id, value) values (b.match_id,b.partner_id,b.bet,b.currency_id,b.value);

            if FOUND then
                return '0';
            else
                return '1';
            end if;
        else
            raise info 'Errore: scommessa già presente da parte di questa società';
            return '5';
        end if;


        exception
            when not_null_violation then
                raise info 'Errore: vincolo di not null violato';
                return '2';
            when unique_violation then
                raise info 'Errore: stai inserendo dati relativi ad un team già presente';
                return '3';
            when foreign_key_violation then
                raise info 'Errore: vincolo chiave esterna violato';
                return '4';
    end;
$result$ language plpgsql;


create or replace function func_update_bet(m_id int,p_id int, b char, curr char(3), new_m_id int, new_b char, new_curr char(3), new_val numeric)
returns char as $result$
    begin

        if (select func_check_bet(row(new_m_id,p_id,new_b,new_val,new_curr)) = 0) then

            update bets
            set match_id = new_m_id,bet = new_b,currency_id = new_curr, value = new_val
            where partner_id = p_id and match_id = m_id and bet = b and currency_id = curr;

            if FOUND then
                return '0';
            else
                return '1';
            end if;
        else
            raise info 'Errore: scommessa già presente da parte di questa società';
            return '5';
        end if;



        exception
            when not_null_violation then
                raise info 'Errore: vincolo di not null violato';
                return '2';
            when unique_violation then
                raise info 'Errore: stai inserendo dati relativi ad un team già presente';
                return '3';
            when foreign_key_violation then
                raise info 'Errore: vincolo chiave esterna violato';
                return '4';
    end;
$result$ language plpgsql;


create or replace function func_delete_bet(m_id int,p_id int, b char, curr char(3))
    returns char as $result$
begin

    delete from bets where match_id = m_id and partner_id = p_id and bet = b and currency_id = curr;

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
    when foreign_key_violation then
        raise info 'Errore: vincolo chiave esterna violato';
        return '4';
end;
$result$ language plpgsql;


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
*/
/*
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


create or replace function func_player_formation_assoc(p initial_formation)
returns char as $result$
    begin
        insert into initial_formation (match_id, player_id) values (p.match_id,p.player_id);

        if FOUND then
            return '0';
        else
            return '1';
        end if;

    exception
        when unique_violation then
            raise info 'Errore: condizione unique violata';
            return '2';
        when not_null_violation then
            raise info 'Errore: vincolo not null violato';
            return '3';
        when foreign_key_violation then
            raise info 'Errore: chiave esterna non presente';
            return '4';

    end;
$result$ language plpgsql;



create or replace function func_insert_player(p player)
returns integer as $result$
    declare
        ret_id player.id%TYPE;
    begin
        insert into player values (p.id,p.name,p.birthday,p.height,p.weight,p.team_id) returning id into ret_id;

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
            raise info 'Errore: stai inserendo dati relativi ad un giocatore già presente';
            return -3;
        when foreign_key_violation then
            raise info 'Errore: chiave esterna non presente';
            return -4;
    end;
$result$ language plpgsql;


create or replace function func_insert_player_attributes(attr player_attribute)
returns char as $result$
    begin
        insert into player_attribute (player_id, date, overall_rating, potential,
                                      preferred_foot, attacking_work_rate, defensive_work_rate,
                                      crossing, finishing, heading_accuracy, short_passing, volleys,
                                      dribbling, curve, free_kick_accuracy, long_passing, ball_control,
                                      acceleration, sprint_speed, agility, reactions, balance, shot_power,
                                      jumping, stamina, strength, long_shots, aggression, interception,
                                      positioning, vision, penalties, marking, standing_tackle, sliding_tackle,
                                      gk_diving, gk_handling, gk_kicking, gk_positioning, gk_reflexes)
        values (attr.player_id, attr.date, attr.overall_rating, attr.potential,
                attr.preferred_foot, attr.attacking_work_rate, attr.defensive_work_rate,
                attr.crossing, attr.finishing, attr.heading_accuracy, attr.short_passing, attr.volleys,
                attr.dribbling, attr.curve, attr.free_kick_accuracy, attr.long_passing, attr.ball_control,
                attr.acceleration, attr.sprint_speed, attr.agility, attr.reactions, attr.balance, attr.shot_power,
                attr.jumping, attr.stamina, attr.strength, attr.long_shots, attr.aggression, attr.interception,
                attr.positioning, attr.vision, attr.penalties, attr.marking, attr.standing_tackle, attr.sliding_tackle,
                attr.gk_diving, attr.gk_handling, attr.gk_kicking, attr.gk_positioning, attr.gk_reflexes);

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
        when unique_violation then
            raise info 'Errore: vincolo unique violato';
            return '5';

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