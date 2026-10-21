<?php
/**
 * VyrnForge UI Modal Component Structure
 * 
 * Parameters:
 * $id - (string) Modal ID
 * $title - (string) Modal title
 * $icon - (string) lucide icon name for title (optional)
 * $form_id - (string) Form ID (optional, if this modal wraps a form)
 * $onsubmit - (string) Form onsubmit JS handler (optional)
 * $body - (string) Modal body content (can also be passed by including the view and passing content, but typically we might just echo it or use this view as a wrapper if we can)
 * 
 * Note: Since CodeIgniter views don't have block yielding easily without a template engine, 
 * this view will just provide the HTML wrapper. It might be easier to use it as an include.
 * Wait, actually, let's create it as a full modal view where body is passed, OR create modal_header.php and modal_footer.php.
 * Let's just create a full modal and assume $body is passed as HTML string, 
 * OR we can just use the standard HTML in the pages and replace classes with vf- classes. 
 * Since the user wants reusable components, let's pass $body.
 */
?>
<?php
$modal_class = isset($class) ? $class : 'max-w-lg';
?>
<div id="<?php echo $id; ?>" class="vf-modal-backdrop fixed inset-0 z-50 bg-[#020617]/80 backdrop-blur-md hidden items-center justify-center p-4">
    <div class="vf-panel w-full <?php echo $modal_class; ?> relative animate-in rounded-2xl bg-[#131B2E] border border-[#1E293B] shadow-[0_25px_50px_-12px_rgba(0,0,0,0.75)]">
        <div class="vf-panel__header border-b border-[#1E293B] pb-3 mb-4 flex items-center justify-between">
            <h3 class="vf-panel__title flex items-center gap-2" <?php echo isset($title_id) ? 'id="'.$title_id.'"' : ''; ?>>
                <?php if (isset($icon)): ?>
                    <i data-lucide="<?php echo $icon; ?>" class="w-5 h-5 text-cyan-400"></i> 
                <?php endif; ?>
                <span><?php echo $title; ?></span>
            </h3>
            <button type="button" onclick="<?php echo isset($onClose) ? $onClose : "document.getElementById('{$id}').classList.add('hidden'); document.getElementById('{$id}').classList.remove('flex');"; ?>" class="text-slate-400 hover:text-white transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <?php if (isset($form_id)): ?>
        <form id="<?php echo $form_id; ?>" <?php echo isset($onsubmit) ? 'onsubmit="'.$onsubmit.'"' : ''; ?> class="vf-stack vf-stack--gap-lg">
        <?php else: ?>
        <div class="vf-stack vf-stack--gap-lg">
        <?php endif; ?>
        
            <div class="vf-panel__body">
                <?php echo isset($content) ? $content : (isset($body) ? $body : ''); ?>
            </div>

            <?php if (!isset($hide_actions) || !$hide_actions): ?>
            <div class="vf-panel__actions border-t border-[#1E293B] pt-4 mt-4 w-full justify-end flex gap-2">
                <?php 
                $this->load->view('components/button', [
                    'text' => 'Batal',
                    'variant' => 'subtle',
                    'attributes' => 'onclick="document.getElementById(\''.$id.'\').classList.add(\'hidden\'); document.getElementById(\''.$id.'\').classList.remove(\'flex\');"'
                ]); 
                ?>
                
                <?php if (isset($form_id)): ?>
                    <?php 
                    $this->load->view('components/button', [
                        'text' => isset($submit_text) ? $submit_text : 'Simpan',
                        'type' => 'submit',
                        'variant' => 'primary',
                        'icon' => 'save',
                        'id' => isset($submit_btn_id) ? $submit_btn_id : ''
                    ]); 
                    ?>
                <?php endif; ?>
            </div>
            <?php endif; ?>

        <?php if (isset($form_id)): ?>
        </form>
        <?php else: ?>
        </div>
        <?php endif; ?>
    </div>
</div>
