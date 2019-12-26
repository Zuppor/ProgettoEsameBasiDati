create type best_players as(
    match_id int,
    a_team int,
    a_name varchar(100),
    a_rating int,
    h_team int,
    h_name varchar(100),
    h_rating int);

create type best_player as(
    team_id int,
    team_h boolean,
    player_name varchar(100),
    rating int
    );

drop type best_player cascade;


--funzione per inserire match nel database. ritorna 0 se è andato a buon fine, 2 se vincoli di not null sono stati violati,
--1 se ci sono violazioni sulle chiave esterne, 3 se i vincoli unique sono violati, 4 se una chiave esterna non è presente
create or replace function func_insert_match(m match)
returns char as $result$
begin
    insert into public.match (id, home_team_id, away_team_id, season, stage, date, a_team_goal, h_team_goal, league_id, country_id, operator_id)
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
          raise info 'Errore: chiave esterna non presente';
          return '4';
        when check_violation then
            raise info 'Errore: check violation';
            return '5';
end;
$result$ language plpgsql;


create or replace function func_delete_match(m_id match.id%TYPE,op_id match.operator_id%TYPE)
returns char as $result$
    begin

        delete from public.match where id = m_id and operator_id = op_id;


        if FOUND then
            return '0';
        else
            return '1';
        end if;
    end;
$result$ language plpgsql;


create or replace function func_update_match(m match)
returns char as $result$
    begin

        update public.match
        set home_team_id = m.home_team_id, away_team_id = m.away_team_id, season = m.season, stage = m.stage, date= m.date, a_team_goal = m.a_team_goal, h_team_goal = m.h_team_goal, league_id = m.league_id, country_id = m.country_id
        where id = m.id and operator_id = m.operator_id;



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


create or replace function func_delete_country(c_id country.id%TYPE)
    returns char as $result$
begin
    delete from country where id = c_id;

    if FOUND then
        return '0';
    else
        return '1';
    end if;

exception
    when case_not_found then
        raise info 'Id league non presente';
        return '2';
end;
$result$ language plpgsql;



create or replace function func_update_country(c country)
    returns char as $result$
begin
    update country
    set name = c.name
    where id = c.id;

    if FOUND then
        return '0';
    else
        return '1';
    end if;

exception
    when unique_violation then
        raise info 'League già presente';
        return '2';
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


create or replace function func_delete_league(l_id league.id%TYPE)
returns char as $result$
    begin
        delete from league where id = l_id;

        if FOUND then
            return '0';
        else
            return '1';
        end if;

        exception
        when case_not_found then
            raise info 'Id league non presente';
            return '2';
    end;
$result$ language plpgsql;



create or replace function func_update_league(l league)
    returns char as $result$
begin
    update league
    set name = l.name
    where id = l.id;

    if FOUND then
        return '0';
    else
        return '1';
    end if;

exception
    when unique_violation then
        raise info 'League già presente';
        return '2';
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


create or replace function func_delete_team(t_id team.id%TYPE)
    returns char as $result$
begin
    delete from team where id = t_id;

    if FOUND then
        return '0';
    else
        return '1';
    end if;

exception
    when case_not_found then
        raise info 'Errore: team non presente';
        return '2';

end;
$result$
    language plpgsql;


create or replace function func_update_team(t team)
    returns char as $result$
begin
    update team
    set short_name = t.short_name,long_name = t.long_name
    where id = t.id;

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






create or replace function get_best_players(match_id public.match.id%TYPE)
returns setof best_player as $$
    declare
        tmp record;
        tmp2 record;
        best best_player;
  begin
      for tmp in (select id,date,home_team_id,away_team_id from public.match m where m.id = match_id order by date desc ) loop
        best.team_id := tmp.home_team_id;
        best.team_h := true;

        for tmp2 in (with q as (select p.name as pname,pa.val as paval
        from player p
                 join player_attribute pa on p.id = pa.player_id and pa.date <= tmp.date and pa.name = 'overall_rating'
        where pa.date >= all(select pat.date
                             from player pl
                            join player_attribute pat on pl.id = pat.player_id and pat.date <= tmp.date and pat.name = 'overall_rating'
                            where pl.id = p.id and pl.team_id = tmp.home_team_id)
        and p.team_id = tmp.home_team_id)
        select *
        from q
        where paval >= all(select paval from q)) loop
                best.player_name := tmp2.pname;
                best.rating := tmp2.paval;

                return next best;
        end loop;

        best.team_id := tmp.away_team_id;
        best.team_h := false;

        for tmp2 in (with q as (select p.name as pname,pa.val as paval
                                from player p
                                         join player_attribute pa on p.id = pa.player_id and pa.date <= tmp.date and pa.name = 'overall_rating'
                                where pa.date >= all(select pat.date
                                                     from player pl
                                                              join player_attribute pat on pl.id = pat.player_id and pat.date <= tmp.date and pat.name = 'overall_rating'
                                                     where pl.id = p.id and pl.team_id = tmp.away_team_id)
                                  and p.team_id = tmp.away_team_id)
                     select *
                     from q
                     where paval >= all(select paval from q)) loop
                best.player_name := tmp2.pname;
                best.rating := tmp2.paval;

                return next best;
            end loop;
      end loop;
  end;
$$ language plpgsql;



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

--todo: la funzione ci mette troppo. per velocizzare, si potrebbe far sì che i dati vengano inseriti soltanto quando cambia il valore
create or replace function func_insert_player_attribute(attr player_attribute)
returns char as $result$
    begin
        insert into player_attribute values (attr.player_id,attr.date,attr.name,attr.val);

        if FOUND then
            return '0';
        else
            return '1';
        end if;

        exception
        when not_null_violation then
            raise info 'Errore: vincolo not null violato';
            return '2';
        when foreign_key_violation then
            raise info 'Errore: chiave etserna non presente';
            return '3';
        when unique_violation then
            raise info 'Errore: vincolo unique violato';
            return '4';
    end;
$result$ language plpgsql;

/*
create or replace function func_insert_player_rate(attr pa_rate)
    returns char as $result$
begin
    --controllo attributo più recente dello stesso giocatore
    --se è uguale, non inserisco
    --se è diverso, inserisco
    if(select p.value from pa_rate p where p.player_id = attr.player_id and p.date = attr.date) <> attr.value then
        insert into pa_rate values (attr.player_id,attr.date,attr.value);
    end if;

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


create or replace function func_insert_player_foot(attr pa_foot)
returns char as $result$
    begin
        --controllo attributo più recente dello stesso giocatore
        --se è uguale, non inserisco
        --se è diverso, inserisco
        if(select p.value from pa_foot p where p.player_id = attr.player_id and p.date = attr.date) <> attr.value then
            insert into pa_foot values (attr.player_id,attr.date,attr.value);
        end if;

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

--todo: mqf
create or replace function func_insert_player_percentage(attr pa_percentage)
returns char as $result$
    begin

        if(select p.value from pa_percentage p where p.player_id = attr.player_id and p.date = attr.date) <> attr.value then
            insert into pa_percentage values (attr.player_id,attr.date,attr.value);
        end if;

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
*/

create or replace function func_refresh_classifica()
    returns trigger
    security definer
as $$
begin
    refresh materialized view classifica;

    return new;
end;
$$ language plpgsql;

create or replace function func_generate_classifica()--todo: sistemare
returns TABLE(score integer,victories integer,draws integer,lost integer) as $$
    declare
        tmpSeason record;
        tmpTeam record;
    begin
        for tmpSeason in (select distinct(season) as year from public.match order by season desc) loop
            for tmpTeam in (select distinct(team.id)  as id from team join match m on team.id = m.away_team_id where m.season = tmpSeason.year) loop

                    with
                    vict as (
                        select sum(vit) as tot
                        from (
                        select count(m.id) as vit
                        from public.match m
                        where tmpTeam.id = m.home_team_id and h_team_goal > a_team_goal
                        union
                        select count(m.id)
                        from public.match m
                        where tmpTeam.id = m.away_team_id and h_team_goal < a_team_goal) as a),
                    draw as (
                        select sum(par) as tot
                        from(
                        select count(m.id) as par
                        from public.match m
                        where tmpTeam.id = m.home_team_id and h_team_goal = a_team_goal
                        union
                         select count(m.id)
                          from public.match m
                          where tmpTeam.id = m.away_team_id and h_team_goal = a_team_goal) as a),
                    lost as (
                        select sum(per) as tot
                        from (
                        select count(m.id) as per
                        from public.match m
                        where tmpTeam.id = m.home_team_id and h_team_goal < a_team_goal
                        union
                        select count(m.id)
                        from public.match m
                        where tmpTeam.id = m.away_team_id and h_team_goal > a_team_goal) as a)

                    select (((vict.tot)*3)+draw.tot) as score,vict.tot as victories,draw.tot as draws, lost.tot as lost;
                    return next;

                end loop;
            end loop;
    end;
    $$
language plpgsql;

drop function func_generate_classifica();