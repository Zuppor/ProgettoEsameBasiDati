create domain percentage as
  smallint default 0 check (value between 0 and 100);

create domain rate as
  char(1) default 'n' check ( value in('l','n','m','h'));

--alter domain rate drop not null