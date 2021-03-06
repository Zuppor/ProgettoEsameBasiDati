create table bet_society(
    id serial primary key not null ,
    short_name varchar(10) not null ,
    long_name varchar(100) not null
);

create table users(
  id serial not null primary key ,
  username varchar(100) not null unique ,
  password char(128) not null,
  salt char(128) not null,
  level smallint not null default 2,
  bet_society_id int references bet_society(id) on update cascade on delete set null default null,
  check ( level between 0 and 2)
);

create table team(
  id serial primary key not null ,
  short_name char(3) not null ,
  long_name varchar(100) not null
);

create table player(
  id serial primary key not null ,
  name varchar(100) not null ,
  birthday date not null ,
  height float4 not null ,
  weight int not null,
  team_id int references team(id) on update cascade on delete set null
);

create table league(
  id serial primary key not null ,
  name varchar(100) not null unique
);

create table country(
  id serial primary key not null ,
  name varchar(100) not null unique
);

create table currency(
  code char(3) not null primary key
);

create table match(
  id serial primary key not null ,
  home_team_id int references team(id) on update cascade on delete no action ,
  away_team_id int references team(id) on update cascade on delete no action ,
  season int not null default extract(year from now()),
  stage int not null ,
  date timestamp not null ,
  a_team_goal int not null default 0,
  h_team_goal int not null default 0,
  league_id int references league(id) on update cascade on delete set null ,
  country_id int not null references country(id) on update cascade on delete set null ,
  operator_id int not null references users(id) on update cascade on delete set null ,
  unique (date,league_id,country_id,home_team_id,away_team_id,stage),
  check ( home_team_id <> away_team_id ),
  check ( a_team_goal >= 0 ),
  check ( h_team_goal >= 0 )
);

create table player_attribute(
    player_id int not null references player(id) on update cascade on delete cascade ,
    date timestamp not null,
    name varchar(50) not null,
    val int not null,
    unique (player_id,date,name)
);

create table bets(
  match_id int references match(id) on update cascade on delete cascade ,
  partner_id int not null references users(id) on update cascade on delete no action ,
  bet bet_domain ,
  value numeric not null default 0,
  currency_id char(3) not null references currency(code) on update cascade on delete no action ,
  primary key (match_id,partner_id,bet,currency_id)
);

create table initial_formation(
  match_id int not null references match(id) on update cascade on delete cascade ,
  player_id int references player(id) on update cascade on delete cascade,
  unique (match_id,player_id)
);

create table login_attempt(
  user_id int not null references users(id) on update cascade on delete cascade ,
  time varchar(30) not null
);