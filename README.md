# borg-demo

	vagrant up


	curl -s 172.20.20.12:8080/life-form -d '["Jean-Luc Picard", "William Riker", "Deanna Troi"]'
	curl -s 172.20.20.12:8080/group -d '["Jean-Luc Picard", "William Riker", "Deanna Troi"]'

	curl -s -X PUT 172.20.20.12:8080/race/humanity -d '{"status": "assimilated"}'
	curl -s 172.20.20.12:8080/race/humanity
