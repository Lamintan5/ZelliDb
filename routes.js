

router.route("/addimage").post(upload.single("img"), (req, res) => {
    try {
        if (!req.file) {
            return res.status(400).json({ error: "No file uploaded." });
        }
        res.json({ path: req.file.filename });
    } catch (e) {
        console.error("Error uploading image:", e);
        res.status(500).json({ error: "Internal server error" });
    }
});

module.exports = router;
