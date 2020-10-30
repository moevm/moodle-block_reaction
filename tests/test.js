const MOODLE_ROOT = 'http://localhost:1010'
const TOKEN = '3e0d77612cd6ed7f9db4671a5af6c006'
const COURSE = 2
const MODULE = 2

const assert = require('assert')
const request = require('supertest')(MOODLE_ROOT)

function get_reaction() {
    return request
        .post('/webservice/rest/server.php')
        .type('form')
        .send({
            'wstoken': TOKEN,
            'wsfunction': 'mse_ld_get_reaction',
            'moduleid': MODULE,
            'moodlewsrestformat': 'json'
        })
}

function set_reaction(reaction) {
    return request
        .post('/webservice/rest/server.php')
        .type('form')
        .send({
            'wstoken': TOKEN,
            'wsfunction': 'mse_ld_set_reaction',
            'moduleid': MODULE,
            'reaction': reaction,
            'moodlewsrestformat': 'json'
        })
}

describe('Reactions requests', function() {

    it('Set like', (done) => {
        set_reaction(1)
            .expect(200)
            .then(() => {
                get_reaction()
                    .expect(200)
                    .then((res) => {
                        assert.equal(res.body, 1)
                        done()
                    })
            })
    })
    
    it('Set dislike', (done) => {
        set_reaction(0)
            .expect(200)
            .then(() => {
                get_reaction()
                    .expect(200)
                    .then((res) => {
                        assert.equal(res.body, 0)
                        done()
                    })
            })
    })
    
    it('Unset reaction', (done) => {
        set_reaction(2)
            .expect(200)
            .then(() => {
                get_reaction()
                    .expect(200)
                    .then((res) => {
                        assert.equal(res.body, 2)
                        done()
                    })
            })
    })
});

function get_visibility() {
    return request
        .post('/webservice/rest/server.php')
        .type('form')
        .send({
            'wstoken': TOKEN,
            'wsfunction': 'mse_ld_get_module_reactions_visibility',
            'moduleid': MODULE,
            'moodlewsrestformat': 'json'
        })
}

function toggle_visibility() {
    return request
        .post('/webservice/rest/server.php')
        .type('form')
        .send({
            'wstoken': TOKEN,
            'wsfunction': 'mse_ld_toggle_module_reaction_visibility',
            'moduleid': MODULE,
            'moodlewsrestformat': 'json'
        })
}

function set_course_reactions_visible(visible) {
    return request
        .post('/webservice/rest/server.php')
        .type('form')
        .send({
            'wstoken': TOKEN,
            'wsfunction': 'mse_ld_set_course_modules_reactions_visible',
            'courseid': COURSE,
            'visible': visible,
            'moodlewsrestformat': 'json'
        })
}

describe('Settings request', function () {
    
    it('Toggle visibility', (done) => {
        get_visibility()
            .expect(200)
            .then((res) => {
                beginVisibility = res.body
                toggle_visibility()
                    .expect(200)
                    .then(() => {
                        get_visibility()
                            .expect(200)
                            .then((res) => {
                                assert.equal(res.body, !beginVisibility)
                                done()
                            })
                    })
            })
    })
    
    it('Toggle visibility 2', (done) => {
        get_visibility()
            .expect(200)
            .then((res) => {
                beginVisibility = res.body
                toggle_visibility()
                    .expect(200)
                    .then(() => {
                        get_visibility()
                            .expect(200)
                            .then((res) => {
                                assert.equal(res.body, !beginVisibility)
                                done()
                            })
                    })
            })
    })
    
    it('Enable course reactions', (done) => {
        set_course_reactions_visible(1)
            .expect(200)
            .then(() => {
                get_visibility()
                    .expect(200)
                    .then((res) => {
                        assert.equal(res.body, true)
                        done()
                    })
            })
    })
    
    it('Disable course reactions', (done) => {
        set_course_reactions_visible(0)
            .expect(200)
            .then(() => {
                get_visibility()
                    .expect(200)
                    .then((res) => {
                        assert.equal(res.body, false)
                        done()
                    })
            })
    })
    
})
