create domain bet_domain as
    char(1) not null check ( value in('a','h','d') );