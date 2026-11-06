/**
 * Digital Academy - Dopamine Gamification & Audio Synthesis Engine
 * 
 * Features:
 * 1. Web Audio API Synthesizer (Zero-latency, 100% offline polyphonic chimes)
 * 2. High-Performance Canvas Confetti Cannon (with offline 2D canvas fallback)
 * 3. XP Rewards, Sound Toggles & Achievement Unlock Toasts
 */

(function(window) {
    'use strict';

    var audioCtx = null;
    var soundEnabled = localStorage.getItem('da_sound') !== 'false';

    // Lazy AudioContext initialization (respects browser autoplay policies)
    function getAudioContext() {
        if (!soundEnabled) return null;
        if (!audioCtx) {
            var AudioContextClass = window.AudioContext || window.webkitAudioContext;
            if (AudioContextClass) {
                audioCtx = new AudioContextClass();
            }
        }
        if (audioCtx && audioCtx.state === 'suspended') {
            audioCtx.resume();
        }
        return audioCtx;
    }

    // Synthesize clean harmonic note
    function playTone(freq, startTime, duration, type, initialGain, peakGain) {
        var ctx = getAudioContext();
        if (!ctx) return;

        try {
            var osc = ctx.createOscillator();
            var gain = ctx.createGain();

            osc.type = type || 'sine';
            osc.frequency.setValueAtTime(freq, ctx.currentTime + startTime);

            gain.gain.setValueAtTime(initialGain || 0.001, ctx.currentTime + startTime);
            gain.gain.exponentialRampToValueAtTime(peakGain || 0.15, ctx.currentTime + startTime + 0.04);
            gain.gain.exponentialRampToValueAtTime(0.0001, ctx.currentTime + startTime + duration);

            osc.connect(gain);
            gain.connect(ctx.destination);

            osc.start(ctx.currentTime + startTime);
            osc.stop(ctx.currentTime + startTime + duration + 0.05);
        } catch (e) {
            // Audio policy or device limitation fallback
        }
    }

    // Native HTML5 Canvas Confetti Fallback (If CDN is blocked or offline)
    function fallbackConfetti() {
        var canvas = document.getElementById('da-confetti-canvas');
        if (!canvas) {
            canvas = document.createElement('canvas');
            canvas.id = 'da-confetti-canvas';
            canvas.style.position = 'fixed';
            canvas.style.top = '0';
            canvas.style.left = '0';
            canvas.style.width = '100vw';
            canvas.style.height = '100vh';
            canvas.style.pointerEvents = 'none';
            canvas.style.zIndex = '99999';
            document.body.appendChild(canvas);
        }

        var ctx = canvas.getContext('2d');
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;

        var particles = [];
        var colors = ['#4f46e5', '#10b981', '#f59e0b', '#ec4899', '#3b82f6', '#8b5cf6', '#eab308'];

        for (var i = 0; i < 90; i++) {
            particles.push({
                x: canvas.width / 2 + (Math.random() - 0.5) * 200,
                y: canvas.height * 0.4,
                vx: (Math.random() - 0.5) * 14,
                vy: -(Math.random() * 12 + 6),
                size: Math.random() * 8 + 5,
                color: colors[Math.floor(Math.random() * colors.length)],
                rotation: Math.random() * 360,
                vRot: (Math.random() - 0.5) * 10,
                opacity: 1
            });
        }

        var startTime = performance.now();
        function animate(now) {
            var elapsed = now - startTime;
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            for (var j = 0; j < particles.length; j++) {
                var p = particles[j];
                p.x += p.vx;
                p.y += p.vy;
                p.vy += 0.35; // Gravity
                p.rotation += p.vRot;
                p.opacity = Math.max(0, 1 - (elapsed / 2500));

                ctx.save();
                ctx.translate(p.x, p.y);
                ctx.rotate((p.rotation * Math.PI) / 180);
                ctx.fillStyle = p.color;
                ctx.globalAlpha = p.opacity;
                ctx.fillRect(-p.size / 2, -p.size / 2, p.size, p.size * 1.4);
                ctx.restore();
            }

            if (elapsed < 2600) {
                requestAnimationFrame(animate);
            } else {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
            }
        }
        requestAnimationFrame(animate);
    }

    var Dopamine = {
        isSoundEnabled: function() {
            return soundEnabled;
        },

        toggleSound: function() {
            soundEnabled = !soundEnabled;
            localStorage.setItem('da_sound', soundEnabled ? 'true' : 'false');
            this.updateSoundIcon();
            if (soundEnabled) {
                this.playPop();
            }
            return soundEnabled;
        },

        updateSoundIcon: function() {
            var icon = document.getElementById('soundIcon');
            var btn = document.getElementById('soundToggleBtn');
            if (icon) {
                icon.className = soundEnabled ? 'fas fa-volume-up text-primary' : 'fas fa-volume-mute text-muted';
            }
            if (btn) {
                btn.title = soundEnabled ? 'Learning Sounds: Active' : 'Learning Sounds: Muted';
            }
        },

        // 1. Play snappy pop / click sound on selection
        playPop: function() {
            var ctx = getAudioContext();
            if (!ctx) return;
            try {
                var osc = ctx.createOscillator();
                var gain = ctx.createGain();
                osc.type = 'triangle';
                osc.frequency.setValueAtTime(600, ctx.currentTime);
                osc.frequency.exponentialRampToValueAtTime(150, ctx.currentTime + 0.05);

                gain.gain.setValueAtTime(0.12, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.05);

                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start();
                osc.stop(ctx.currentTime + 0.06);
            } catch (e) {}
        },

        // 2. Play high-grade success chord (Major triad arpeggio: C5, E5, G5, C6)
        playSuccessChime: function() {
            // Notes: C5 (523.25), E5 (659.25), G5 (783.99), C6 (1046.50)
            playTone(523.25, 0.00, 0.5, 'sine', 0.01, 0.18);
            playTone(659.25, 0.10, 0.5, 'sine', 0.01, 0.18);
            playTone(783.99, 0.20, 0.6, 'sine', 0.01, 0.20);
            playTone(1046.50, 0.32, 0.9, 'triangle', 0.01, 0.25);
        },

        // 3. Play triumphant graduation fanfare
        playFanfare: function() {
            // Triumphant herald: G4 -> C5 -> E5 -> G5 -> C6 held
            playTone(392.00, 0.00, 0.3, 'sine', 0.01, 0.15);
            playTone(523.25, 0.12, 0.3, 'sine', 0.01, 0.18);
            playTone(659.25, 0.24, 0.3, 'sine', 0.01, 0.18);
            playTone(783.99, 0.36, 0.4, 'sine', 0.01, 0.22);
            playTone(1046.50, 0.50, 1.2, 'triangle', 0.01, 0.28);
            playTone(1318.51, 0.54, 1.2, 'sine', 0.01, 0.15);
        },

        // 4. Play gentle encouraging tone on wrong attempt
        playGentleFailure: function() {
            playTone(280.00, 0.00, 0.3, 'triangle', 0.01, 0.12);
            playTone(240.00, 0.14, 0.4, 'sine', 0.01, 0.10);
        },

        // 5. Fire multi-directional confetti cannon
        fireConfettiCannon: function() {
            if (typeof window.confetti === 'function') {
                // High-velocity dual cannon blast
                window.confetti({
                    particleCount: 70,
                    spread: 70,
                    origin: { y: 0.65, x: 0.3 },
                    colors: ['#4f46e5', '#10b981', '#f59e0b', '#ec4899', '#3b82f6', '#8b5cf6']
                });
                window.confetti({
                    particleCount: 70,
                    spread: 70,
                    origin: { y: 0.65, x: 0.7 },
                    colors: ['#4f46e5', '#10b981', '#f59e0b', '#ec4899', '#3b82f6', '#8b5cf6']
                });
            } else {
                fallbackConfetti();
            }
        },

        // 6. Continuous 3-second celebratory fireworks display
        fireContinuousCelebration: function() {
            if (typeof window.confetti === 'function') {
                var duration = 2.5 * 1000;
                var end = Date.now() + duration;

                (function frame() {
                    window.confetti({
                        particleCount: 5,
                        angle: 60,
                        spread: 55,
                        origin: { x: 0 },
                        colors: ['#4f46e5', '#10b981', '#f59e0b', '#ffd700']
                    });
                    window.confetti({
                        particleCount: 5,
                        angle: 120,
                        spread: 55,
                        origin: { x: 1 },
                        colors: ['#4f46e5', '#10b981', '#f59e0b', '#ffd700']
                    });

                    if (Date.now() < end) {
                        requestAnimationFrame(frame);
                    }
                }());
            } else {
                fallbackConfetti();
            }
        },

        // 7. Show floating XP Gain Toast (+50 XP, +150 XP)
        showXpGainToast: function(amount, title) {
            var container = document.getElementById('da-gamification-toasts');
            if (!container) {
                container = document.createElement('div');
                container.id = 'da-gamification-toasts';
                container.style.position = 'fixed';
                container.style.top = '80px';
                container.style.right = '24px';
                container.style.zIndex = '100000';
                container.style.display = 'flex';
                container.style.flexDirection = 'column';
                container.style.gap = '12px';
                container.style.pointerEvents = 'none';
                document.body.appendChild(container);
            }

            var toast = document.createElement('div');
            toast.className = 'da-xp-toast';
            toast.style.background = 'linear-gradient(135deg, rgba(15, 23, 42, 0.95), rgba(30, 41, 59, 0.98))';
            toast.style.border = '1px solid rgba(212, 175, 55, 0.4)';
            toast.style.borderRadius = '14px';
            toast.style.padding = '12px 18px';
            toast.style.color = '#ffffff';
            toast.style.display = 'flex';
            toast.style.alignItems = 'center';
            toast.style.gap = '12px';
            toast.style.boxShadow = '0 10px 30px rgba(0, 0, 0, 0.4), 0 0 15px rgba(212, 175, 55, 0.2)';
            toast.style.transform = 'translateY(-20px)';
            toast.style.opacity = '0';
            toast.style.transition = 'all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1)';

            toast.innerHTML = [
                '<div style="width: 38px; height: 38px; background: rgba(212, 175, 55, 0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #ffd700; font-size: 1.1rem; border: 1px solid rgba(212, 175, 55, 0.5);">',
                '<i class="fas fa-bolt"></i>',
                '</div>',
                '<div>',
                '<div style="font-weight: 800; font-size: 0.95rem; color: #f6e05e;">+' + amount + ' XP Awarded</div>',
                '<div style="font-size: 0.76rem; color: #94a3b8;">' + (title || 'Consistent Learning Progress') + '</div>',
                '</div>'
            ].join('');

            container.appendChild(toast);

            setTimeout(function() {
                toast.style.transform = 'translateY(0)';
                toast.style.opacity = '1';
            }, 20);

            setTimeout(function() {
                toast.style.transform = 'translateY(-25px)';
                toast.style.opacity = '0';
                setTimeout(function() {
                    if (toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                }, 400);
            }, 3200);
        },

        // 8. Unlocked Achievement Modal
        showAchievementModal: function(badgeTitle, badgeDesc, iconName) {
            this.playFanfare();
            this.fireConfettiCannon();

            var overlay = document.createElement('div');
            overlay.className = 'da-achievement-overlay';
            overlay.style.position = 'fixed';
            overlay.style.inset = '0';
            overlay.style.background = 'rgba(0, 0, 0, 0.7)';
            overlay.style.backdropFilter = 'blur(6px)';
            overlay.style.zIndex = '999999';
            overlay.style.display = 'flex';
            overlay.style.alignItems = 'center';
            overlay.style.justifyContent = 'center';
            overlay.style.padding = '20px';

            overlay.innerHTML = [
                '<div style="background: #111827; border: 2px solid #d4af37; border-radius: 20px; max-width: 440px; width: 100%; padding: 32px; text-align: center; box-shadow: 0 0 50px rgba(212, 175, 55, 0.35); position: relative; animation: popIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);">',
                '<div style="width: 80px; height: 80px; margin: 0 auto 16px; background: rgba(212, 175, 55, 0.15); border: 2px solid #d4af37; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.2rem; color: #ffd700;">',
                '<i class="' + (iconName || 'fas fa-medal') + '"></i>',
                '</div>',
                '<span style="background: rgba(212, 175, 55, 0.2); border: 1px solid rgba(212, 175, 55, 0.4); color: #ffd700; font-size: 0.72rem; text-transform: uppercase; letter-spacing: 2px; padding: 4px 12px; border-radius: 999px; font-weight: 800;">Achievement Unlocked</span>',
                '<h3 style="color: #ffffff; font-weight: 800; font-size: 1.4rem; margin: 12px 0 6px;">' + badgeTitle + '</h3>',
                '<p style="color: #94a3b8; font-size: 0.9rem; margin-bottom: 24px; line-height: 1.5;">' + badgeDesc + '</p>',
                '<button type="button" id="da-close-badge-btn" style="background: linear-gradient(135deg, #4f46e5, #7c3aed); border: none; color: #fff; padding: 10px 28px; border-radius: 999px; font-weight: 700; cursor: pointer; font-size: 0.9rem; box-shadow: 0 4px 15px rgba(79, 70, 229, 0.4);">Awesome! Continue</button>',
                '</div>'
            ].join('');

            document.body.appendChild(overlay);

            var closeBtn = overlay.querySelector('#da-close-badge-btn');
            if (closeBtn) {
                closeBtn.addEventListener('click', function() {
                    overlay.remove();
                });
            }
            overlay.addEventListener('click', function(e) {
                if (e.target === overlay) {
                    overlay.remove();
                }
            });
        }
    };

    // Attach to global window
    window.Dopamine = Dopamine;

    // Update sound icon on page load
    document.addEventListener('DOMContentLoaded', function() {
        Dopamine.updateSoundIcon();
    });

})(window);
