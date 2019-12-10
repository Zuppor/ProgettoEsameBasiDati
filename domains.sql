create domain bet_domain as
    char(1) not null check ( value in('a','h','d') );

/*create domain percentage as
  smallint default 0 check (value between 0 and 100);
/*
create domain rate as
  char(1) default 'n' check ( value in('l','n','m','h'));

create domain foot as
    char(1) default 'r' check(value in ('r','l'));

/*create domain attributename as
    varchar(50) check (value in('overall_rating','potential','preferred_foot',
                                'attacking_work_rate','defensive_work_rate',
                                'crossing','finishing','heading_accuracy',
                                'short_passing','volleys','dribbling',
                                'curve','free_kick_accuracy','long_passing',
                                'ball_control','acceleration','sprint_speed',
                                'agility','reactions','balance','shot_power',
                                'jumping','stamina','strength','long_shots',
                                'aggression','interception','positioning',
                                'vision','penalties','marking','standing_tackle',
                                'sliding_tackle','gk_diving','gk_handling',
                                'gk_kicking','gk_positioning','gk_reflexes'));