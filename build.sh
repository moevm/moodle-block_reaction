set -e

cd /code/src/amd
uglifyjs-folder src -eo build --config-file '../uglifyjs.config.json'

cd /
rm -rf moodle_block_reaction
mkdir moodle_block_reaction

cp -r /code/src/* /moodle_block_reaction

zip moodle_block_reaction.zip -q -r /moodle_block_reaction

rm -f /code/moodle_block_reaction.zip
mv moodle_block_reaction.zip /code/
