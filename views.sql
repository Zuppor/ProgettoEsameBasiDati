create materialized view classifica
as
  select date,l.name,stage,season,t.long_name as team_a,t2.long_name as team_h,h_team_goal,a_team_goal
  from match
  join league l on match.league_id = l.id
  join team t on match.away_team_id = t.id
  join team t2 on match.home_team_id = t2.id
  order by season desc, stage desc ;

drop materialized view classifica;