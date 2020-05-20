fetch("https://postcodejp-api.p.rapidapi.com/postcodes?postcode=1000001", {
	"method": "GET",
	"headers": {
		"x-rapidapi-host": "postcodejp-api.p.rapidapi.com",
		"x-rapidapi-key": "3326c4fea1msh3352fd81cad4962p17d7ffjsnaa7087b43881"
	}
})
.then(response => {
	console.log(response);
})
.catch(err => {
	console.log(err);
});


  echo $dat["data"][0]["allAddress"];
