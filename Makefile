p:
	make -C server prepare

q:
	make -C server analyse

x:
	make -C server fix

i:
	cd client; npm install
	make -C server install-app

t:
	make -C server tests

c:
	make -C server tests-coverage

s:
	make -C server server-start
	cd client; ng serve

d:
	make -C server server-start