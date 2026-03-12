create table public.current_schema as 
select current_setting('search_path');